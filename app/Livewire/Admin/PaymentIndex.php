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

            // Transition parent order back to cancelled
            if ($payment->order) {
                $payment->order->update([
                    'status' => 'cancelled',
                ]);

                // Revert inventory stock via save to fire model event hooks
                foreach ($payment->order->products as $product) {
                    $product->adjustment_reason = "Requisition cancellation (M-Pesa Refund)";
                    $product->stock = $product->stock + $product->pivot->quantity;
                    $product->save();
                }

                // Revoke loyalty points earned
                $user = \App\Models\User::where('email', $payment->order->client->email)->first();
                if ($user) {
                    $pointsEarned = (int) ($payment->order->total_amount / 100);
                    if ($pointsEarned > 0) {
                        $user->decrement('loyalty_points', min($user->loyalty_points, $pointsEarned));
                        \App\Models\LoyaltyTransaction::create([
                            'user_id' => $user->id,
                            'points' => -$pointsEarned,
                            'type' => 'redeem',
                            'description' => "Points revoked for refunded order #NB-ORD-" . str_pad($payment->order->id, 4, '0', STR_PAD_LEFT),
                        ]);
                    }
                }
            }

            Log::info("Simulated B2C M-Pesa Refund processed for Receipt: {$payment->mpesa_receipt_number}");
        });

        $this->showRefundModal = false;
        $this->selectedPaymentId = null;
        session()->flash('message', 'M-Pesa refund simulated successfully. Requisition status reverted.');
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
