<?php

namespace App\Livewire\Admin;

use App\Models\EtimsInvoice;
use App\Services\EtimsService;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Admin\Traits\WithIntelligentSearch;

class TaxIndex extends Component
{
    use WithPagination, WithIntelligentSearch;

    public string $search = '';
    public string $statusFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Retry sending failed invoice to KRA eTIMS server.
     */
    public function retryTransmission(int $invoiceId, EtimsService $etims): void
    {
        $invoice = EtimsInvoice::findOrFail($invoiceId);
        
        $success = $etims->transmitToKra($invoice);

        if ($success) {
            session()->flash('message', 'eTIMS invoice #' . $invoice->internal_invoice_number . ' transmitted to KRA successfully.');
        } else {
            session()->flash('error', 'eTIMS retry transmission failed: ' . $invoice->error_log_payload);
        }
    }

    public function render()
    {
        $query = EtimsInvoice::with(['order.client']);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->search)) {
            $this->parseAndApplySearch(
                $query,
                $this->search,
                ['internal_invoice_number', 'cu_invoice_number', 'gross_amount', 'taxable_amount', 'vat_amount', 'order.client.contact_name', 'order.client.company_name'],
                [
                    'status' => 'status',
                    'gross' => 'gross_amount',
                    'vat' => 'vat_amount',
                ]
            );
        }

        // Metrics calculations
        $totalVat = EtimsInvoice::where('status', 'transmitted')->sum('vat_amount');
        $successCount = EtimsInvoice::where('status', 'transmitted')->count();
        $failedCount = EtimsInvoice::where('status', 'failed')->count();

        return view('livewire.admin.tax-index', [
            'invoices' => $query->latest()->paginate(15),
            'totalVat' => $totalVat,
            'successCount' => $successCount,
            'failedCount' => $failedCount,
        ])->layout('components.layouts.admin');
    }
}
