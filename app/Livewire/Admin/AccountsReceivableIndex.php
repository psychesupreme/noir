<?php

namespace App\Livewire\Admin;

use App\Models\AccountsReceivableInvoice;
use App\Models\AccountsReceivablePayment;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Admin\Traits\WithIntelligentSearch;

class AccountsReceivableIndex extends Component
{
    use WithPagination, WithIntelligentSearch;

    public string $search = '';
    public string $statusFilter = 'all';

    // Record Payment Modal State
    public bool $showPaymentModal = false;
    public ?int $selectedInvoiceId = null;
    public int $paymentAmount = 0;
    public string $paymentMethod = 'bank_transfer';
    public string $paymentReference = '';

    // Adjust Credit Modal State
    public bool $showCreditModal = false;
    public ?int $selectedClientId = null;
    public int $creditLimit = 0;
    public string $paymentTerms = 'net_30';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Open confirmation modal to record an invoice paydown.
     */
    public function openPaymentModal(int $invoiceId): void
    {
        $invoice = AccountsReceivableInvoice::findOrFail($invoiceId);
        $this->selectedInvoiceId = $invoice->id;
        $this->paymentAmount = $invoice->balance_due;
        $this->paymentMethod = 'bank_transfer';
        $this->paymentReference = '';
        $this->showPaymentModal = true;
    }

    /**
     * Store manual B2B payment log and decrement client outstanding balance.
     */
    public function recordPayment(): void
    {
        $this->validate([
            'paymentAmount' => 'required|integer|min:100',
            'paymentMethod' => 'required|in:bank_transfer,cheque,cash,mpesa',
            'paymentReference' => 'nullable|string|max:100',
        ]);

        if (!$this->selectedInvoiceId) {
            return;
        }

        $invoice = AccountsReceivableInvoice::with('client')->findOrFail($this->selectedInvoiceId);

        if ($this->paymentAmount > $invoice->balance_due) {
            $this->addError('paymentAmount', 'Payment amount cannot exceed outstanding balance of ' . number_format($invoice->balance_due) . ' KSH.');
            return;
        }

        DB::transaction(function () use ($invoice) {
            // 1. Log AR Payment Record
            AccountsReceivablePayment::create([
                'ar_invoice_id' => $invoice->id,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'reference_number' => $this->paymentReference ?: null,
                'recorded_at' => now(),
                'recorded_by_user_id' => auth()->id(),
            ]);

            // 2. Update Invoice Paid Amount
            $newPaid = $invoice->amount_paid + $this->paymentAmount;
            $newStatus = $newPaid >= $invoice->amount_due ? 'paid' : 'partially_paid';
            $invoice->update([
                'amount_paid' => $newPaid,
                'status' => $newStatus,
            ]);

            // 3. Decrement Client's Outstanding Balance
            if ($invoice->client) {
                $invoice->client->decrement('outstanding_balance', $this->paymentAmount);
            }
        });

        $this->showPaymentModal = false;
        $this->selectedInvoiceId = null;
        session()->flash('message', 'Payment recorded successfully. Balance reconciled.');
    }

    /**
     * Open confirmation modal to adjust client B2B parameters.
     */
    public function openCreditModal(int $clientId): void
    {
        $client = Client::findOrFail($clientId);
        $this->selectedClientId = $client->id;
        $this->creditLimit = $client->credit_limit;
        $this->paymentTerms = $client->payment_terms ?: 'net_30';
        $this->showCreditModal = true;
    }

    /**
     * Save Client B2B credit profile details.
     */
    public function saveCreditProfile(): void
    {
        $this->validate([
            'creditLimit' => 'required|integer|min:0',
            'paymentTerms' => 'required|in:prepaid,net_30,cod',
        ]);

        if (!$this->selectedClientId) {
            return;
        }

        $client = Client::findOrFail($this->selectedClientId);
        $client->update([
            'credit_limit' => $this->creditLimit,
            'payment_terms' => $this->paymentTerms,
        ]);

        $this->showCreditModal = false;
        $this->selectedClientId = null;
        session()->flash('message', 'Client corporate credit profile updated successfully.');
    }

    public function render()
    {
        $query = AccountsReceivableInvoice::with(['order.client', 'client']);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->search)) {
            $this->parseAndApplySearch(
                $query,
                $this->search,
                ['status', 'amount_due', 'order.id', 'client.company_name', 'client.contact_name'],
                [
                    'status' => 'status',
                    'due' => 'amount_due',
                ]
            );
        }

        // Metrics calculations (Aging and Outstanding totals)
        $totalOutstanding = AccountsReceivableInvoice::where('status', '!=', 'paid')
            ->get()
            ->sum(fn($inv) => $inv->balance_due);

        // Aging buckets (0-30, 31-60, 60+ days overdue)
        $aging0to30 = AccountsReceivableInvoice::where('status', '!=', 'paid')
            ->where('due_at', '>=', now())
            ->get()
            ->sum(fn($inv) => $inv->balance_due);

        $aging31to60 = AccountsReceivableInvoice::where('status', '!=', 'paid')
            ->where('due_at', '<', now())
            ->where('due_at', '>=', now()->subDays(30))
            ->get()
            ->sum(fn($inv) => $inv->balance_due);

        $aging60Plus = AccountsReceivableInvoice::where('status', '!=', 'paid')
            ->where('due_at', '<', now()->subDays(30))
            ->get()
            ->sum(fn($inv) => $inv->balance_due);

        // Corporate accounts listing to configure limits
        $corporateClients = Client::where('payment_terms', 'net_30')
            ->orWhere('credit_limit', '>', 0)
            ->get();

        return view('livewire.admin.accounts-receivable-index', [
            'invoices' => $query->latest()->paginate(15),
            'corporateClients' => $corporateClients,
            'totalOutstanding' => $totalOutstanding,
            'aging0to30' => $aging0to30,
            'aging31to60' => $aging31to60,
            'aging60Plus' => $aging60Plus,
        ])->layout('components.layouts.admin', ['title' => 'Noir & Bloom | Accounts Receivable']);
    }
}
