<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Admin\Traits\WithIntelligentSearch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentIndex extends Component
{
    use WithPagination, WithIntelligentSearch;

    public string $search = '';
    public string $statusFilter = 'all';

    // Refund Modal state
    public bool $showRefundModal = false;
    public ?int $selectedPaymentId = null;
    public string $refundReason = '';

    // Edit Modal state
    public bool $showEditModal = false;
    public ?int $editingPaymentId = null;
    public string $editingStatus = 'pending';
    public string $editingReceiptNumber = '';
    public string $editingResultDesc = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Open confirmation dialog for simulated refund.
     */
    public function confirmRefund(int $paymentId): void
    {
        $this->selectedPaymentId = $paymentId;
        $this->refundReason = '';
        $this->showRefundModal = true;
    }

    /**
     * Simulate Safaricom M-Pesa B2C Refund transmission and update local state.
     */
    public function processRefund(): void
    {
        $this->validate([
            'refundReason' => 'required|string|min:5|max:255',
        ]);

        if (!$this->selectedPaymentId) {
            return;
        }

        $payment = Payment::with('order')->findOrFail($this->selectedPaymentId);

        DB::transaction(function () use ($payment) {
            // Update payment state
            $payment->update([
                'status' => 'failed',
                'result_description' => 'Refunded: ' . $this->refundReason,
            ]);

            // Transition parent order back to cancelled via OrderService
            if ($payment->order) {
                app(\App\Services\OrderService::class)->cancel($payment->order);
            }

            Log::info("Simulated B2C M-Pesa Refund processed for Receipt: {$payment->mpesa_receipt_number}");
        });

        $this->showRefundModal = false;
        $this->selectedPaymentId = null;
        session()->flash('message', 'M-Pesa refund simulated successfully. Requisition status reverted.');
    }

    /**
     * Open confirmation modal to edit payment status.
     */
    public function openEditModal(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);
        $this->editingPaymentId = $payment->id;
        $this->editingStatus = $payment->status;
        $this->editingReceiptNumber = $payment->mpesa_receipt_number ?? '';
        $this->editingResultDesc = $payment->result_description ?? '';
        $this->showEditModal = true;
    }

    /**
     * Save payment status edits and trigger order transitions.
     */
    public function savePaymentStatus(): void
    {
        $this->validate([
            'editingStatus' => 'required|in:pending,completed,failed',
            'editingReceiptNumber' => $this->editingStatus === 'completed' ? 'required|string|min:5|max:100' : 'nullable|string|max:100',
            'editingResultDesc' => 'nullable|string|max:255',
        ]);

        if (!$this->editingPaymentId) {
            return;
        }

        $payment = Payment::with('order')->findOrFail($this->editingPaymentId);
        $oldStatus = $payment->status;
        $newStatus = $this->editingStatus;

        DB::transaction(function () use ($payment, $oldStatus, $newStatus) {
            $payment->update([
                'status' => $newStatus,
                'mpesa_receipt_number' => ($newStatus === 'completed' && trim($this->editingReceiptNumber) !== '') ? trim($this->editingReceiptNumber) : ($newStatus === 'pending' ? null : $payment->mpesa_receipt_number),
                'result_description' => $this->editingResultDesc,
            ]);

            // If transitioning to completed, approve the order
            if ($newStatus === 'completed' && $oldStatus !== 'completed' && $payment->order) {
                app(\App\Services\OrderService::class)->approve($payment->order);
            }

            // If transitioning to failed, cancel the order
            if ($newStatus === 'failed' && $oldStatus !== 'failed' && $payment->order) {
                app(\App\Services\OrderService::class)->cancel($payment->order);
            }
        });

        $this->showEditModal = false;
        $this->editingPaymentId = null;
        session()->flash('message', 'Payment details updated successfully.');
    }

    public function render()
    {
        $query = Payment::with(['order.client']);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->search)) {
            $this->parseAndApplySearch(
                $query,
                $this->search,
                ['mpesa_receipt_number', 'phone_number', 'result_description', 'amount', 'order.client.contact_name', 'order.client.company_name'],
                [
                    'status' => 'status',
                    'amount' => 'amount',
                    'receipt' => 'mpesa_receipt_number',
                ]
            );
        }

        // Metrics calculations
        $totalVolume = Payment::where('status', 'completed')->sum('amount');
        $pendingCount = Payment::where('status', 'pending')->count();
        $failedCount = Payment::where('status', 'failed')->count();

        return view('livewire.admin.payment-index', [
            'payments' => $query->latest()->paginate(15),
            'totalVolume' => $totalVolume,
            'pendingCount' => $pendingCount,
            'failedCount' => $failedCount,
        ])->layout('components.layouts.admin');
    }
}
