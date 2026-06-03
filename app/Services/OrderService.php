<?php

namespace App\Services;

use App\Models\Order;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use App\Models\EtimsInvoice;
use App\Jobs\TransmitEtimsInvoiceJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected EtimsService $etims
    ) {}

    /**
     * Transition order to approved, performing stock changes, loyalty awards, and KRA transmissions.
     */
    public function approve(Order $order): void
    {
        if (in_array($order->status, ['approved', 'processing', 'delivered'])) {
            return;
        }

        DB::transaction(function () use ($order) {
            $oldStatus = $order->status;

            // 1. Update status first
            $order->update(['status' => 'approved']);

            // 2. Decrement Stock via save to fire model event hooks
            foreach ($order->products as $product) {
                $product->adjustment_reason = "Fulfillment of Order #NB-ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT);
                $product->adjustment_branch_id = $order->branch_id;
                $product->stock = max(0, $product->stock - $product->pivot->quantity);
                $product->save();
            }

            // 3. Award Loyalty Points to client if registered
            if ($order->client && !empty($order->client->email)) {
                $user = User::where('email', $order->client->email)->first();
                if ($user) {
                    $pointsEarned = (int) ($order->total_amount / 100);
                    if ($pointsEarned > 0) {
                        $user->increment('loyalty_points', $pointsEarned);
                        LoyaltyTransaction::create([
                            'user_id' => $user->id,
                            'points' => $pointsEarned,
                            'type' => 'earn',
                            'description' => "Points earned on order #NB-ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                        ]);
                    }
                }
            }

            // 4. KRA eTIMS Transmission
            if (!$order->etimsInvoice) {
                $invoice = $this->etims->initializeFiscalInvoice($order);
                TransmitEtimsInvoiceJob::dispatch($invoice)->afterCommit();
            }

            // 5. Invalidate dashboard stats cache
            Cache::forget('dashboard_stats');

            Log::info("Order approved successfully through OrderService: Order ID: {$order->id}");
        });
    }

    /**
     * Cancel an order, reverting stock adjustments and revoking loyalty points.
     */
    public function cancel(Order $order): void
    {
        if ($order->status === 'cancelled') {
            return;
        }

        $wasFulfillmentStatus = in_array($order->status, ['approved', 'processing', 'delivered']);

        DB::transaction(function () use ($order, $wasFulfillmentStatus) {
            $oldStatus = $order->status;

            // 1. Update status
            $order->update(['status' => 'cancelled']);

            if ($wasFulfillmentStatus) {
                // 2. Revert Stock via save to fire model event hooks
                foreach ($order->products as $product) {
                    $product->adjustment_reason = "Requisition cancellation of Order #NB-ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT);
                    $product->adjustment_branch_id = $order->branch_id;
                    $product->stock = $product->stock + $product->pivot->quantity;
                    $product->save();
                }

                // 3. Revoke Loyalty Points
                if ($order->client && !empty($order->client->email)) {
                    $user = User::where('email', $order->client->email)->first();
                    if ($user) {
                        $pointsEarned = (int) ($order->total_amount / 100);
                        if ($pointsEarned > 0) {
                            $user->decrement('loyalty_points', min($user->loyalty_points, $pointsEarned));
                            LoyaltyTransaction::create([
                                'user_id' => $user->id,
                                'points' => -$pointsEarned,
                                'type' => 'redeem',
                                'description' => "Points revoked for cancelled order #NB-ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                            ]);
                        }
                    }
                }
            }

            // 4. Cancel eTIMS Invoice if it exists
            if ($order->etimsInvoice) {
                $order->etimsInvoice->update(['status' => 'cancelled']);
            }

            // 5. Invalidate dashboard stats cache
            Cache::forget('dashboard_stats');

            Log::info("Order cancelled successfully through OrderService: Order ID: {$order->id}");
        });
    }
}
