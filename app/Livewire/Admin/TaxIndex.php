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

    /**
     * Stream CSV audit logs for the filtered tax dataset.
     */
    public function exportAudits()
    {
        $query = EtimsInvoice::with(['order.client', 'order.payments']);

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

        $filename = 'kra_etims_audit_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->streamDownload(function() use ($query) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Invoice Date',
                'Internal Invoice Number',
                'KRA CU Invoice Number',
                'Client Name',
                'Client KRA PIN',
                'M-Pesa Receipt',
                'Gross Amount (KES)',
                'Taxable Base (KES)',
                'VAT Amount 16% (KES)',
                'Status'
            ]);

            $query->chunk(200, function ($invoices) use ($file) {
                foreach ($invoices as $invoice) {
                    $order = $invoice->order;
                    $client = $order?->client;
                    $completedPayment = $order?->payments?->first(fn($p) => $p->status === 'completed');

                    fputcsv($file, [
                        $invoice->created_at->format('Y-m-d H:i:s'),
                        $invoice->internal_invoice_number,
                        $invoice->cu_invoice_number ?: 'N/A',
                        $client ? ($client->company_name ?: $client->contact_name) : 'Guest Client',
                        $client?->kra_pin ?: 'NONRESIDENT',
                        $completedPayment?->mpesa_receipt_number ?: 'N/A',
                        $invoice->gross_amount,
                        $invoice->taxable_amount,
                        $invoice->vat_amount,
                        strtoupper($invoice->status),
                    ]);
                }
            });

            fclose($file);
        }, $filename, $headers);
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
        ])->layout('components.layouts.admin', ['title' => 'Noir & Bloom | Tax & eTIMS']);
    }
}
