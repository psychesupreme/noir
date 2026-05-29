<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Order;
use App\Services\EtimsService;
use Livewire\Component;

class OrderIndex extends Component
{
    /**
     * Manually override the assigned branch hub allocation for fulfillment optimizations.
     */
    public function updateBranch(int $orderId, int $branchId): void
    {
        Order::where('id', $orderId)->update(['branch_id' => $branchId]);
    }

    /**
     * Update order state and automatically register compliance parameters on approval events.
     */
    public function updateStatus(int $orderId, string $newStatus, EtimsService $etims): void
    {
        $validStatuses = ['pending', 'approved', 'processing', 'delivered', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            return;
        }

        $order = Order::with(['client', 'products'])->find($orderId);
        if (!$order) {
            return;
        }

        $oldStatus = $order->status;
        if ($oldStatus === $newStatus) {
            return;
        }

        $order->update(['status' => $newStatus]);

        // 1. Inventory & Loyalty Program Operations
        // Transition from any status to Approved/Processing/Delivered (Fulfillment started/completed)
        $isFulfillmentStatus = in_array($newStatus, ['approved', 'processing', 'delivered']);
        $wasFulfillmentStatus = in_array($oldStatus, ['approved', 'processing', 'delivered']);

        if ($isFulfillmentStatus && !$wasFulfillmentStatus) {
            // Decrement Stock
            foreach ($order->products as $product) {
                $product->decrement('stock', $product->pivot->quantity);
            }

            // Award Loyalty Points to corresponding User if registered
            $user = \App\Models\User::where('email', $order->client->email)->first();
            if ($user) {
                $pointsEarned = (int) ($order->total_amount / 100);
                if ($pointsEarned > 0) {
                    $user->increment('loyalty_points', $pointsEarned);
                    \App\Models\LoyaltyTransaction::create([
                        'user_id' => $user->id,
                        'points' => $pointsEarned,
                        'type' => 'earn',
                        'description' => "Points earned on order #NB-ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }

        // Transition from Approved/Processing/Delivered to Cancelled (Fulfillment reverted)
        if ($newStatus === 'cancelled' && $wasFulfillmentStatus) {
            // Revert Stock
            foreach ($order->products as $product) {
                $product->increment('stock', $product->pivot->quantity);
            }

            // Revoke Loyalty Points
            $user = \App\Models\User::where('email', $order->client->email)->first();
            if ($user) {
                $pointsEarned = (int) ($order->total_amount / 100);
                if ($pointsEarned > 0) {
                    $user->decrement('loyalty_points', min($user->loyalty_points, $pointsEarned));
                    \App\Models\LoyaltyTransaction::create([
                        'user_id' => $user->id,
                        'points' => -$pointsEarned,
                        'type' => 'redeem',
                        'description' => "Points revoked for cancelled order #NB-ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }

        // 2. KRA eTIMS Transmission on Approval
        if ($newStatus === 'approved') {
            if (!$order->etimsInvoice) {
                $invoice = $etims->initializeFiscalInvoice($order);
                $etims->transmitToKra($invoice);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.order-index', [
            'orders'   => Order::with(['client', 'products', 'etimsInvoice', 'branch'])->latest()->get(),
            'branches' => Branch::where('is_active', true)->get()
        ])->layout('components.layouts.admin');
    }
}