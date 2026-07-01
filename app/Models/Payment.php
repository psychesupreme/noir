<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'mpesa_receipt_number',
        'phone_number',
        'amount',
        'status',
        'merchant_request_id',
        'checkout_request_id',
        'result_description'
    ];

    /**
     * Get the parent corporate order tied to this payment transaction record.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected static function booted(): void
    {
        static::saved(function ($payment) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });

        static::deleted(function ($payment) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });

        static::updated(function ($payment) {
            if ($payment->isDirty('status')) {
                $oldStatus = $payment->getOriginal('status');
                $newStatus = $payment->status;

                \App\Models\SystemLog::write('info', 'payment', "Payment ID {$payment->id} status updated from {$oldStatus} to {$newStatus}.", [
                    'payment_id' => $payment->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'order_id' => $payment->order_id,
                ]);

                // Create user notification on payment success
                if ($newStatus === 'completed') {
                    // Eagerly load order/client if not set
                    $order = $payment->order;
                    if ($order && $order->client && $order->client->user_id) {
                        \App\Models\Notification::create([
                            'user_id' => $order->client->user_id,
                            'title' => 'Payment Received',
                            'message' => "Your payment of " . number_format($payment->amount) . " KSH for order #NB-ORD-{$payment->order_id} has been processed successfully.",
                            'type' => 'success',
                        ]);
                    }
                }

                // Create user notification on payment failure
                if ($newStatus === 'failed') {
                    $order = $payment->order;
                    if ($order && $order->client && $order->client->user_id) {
                        \App\Models\Notification::create([
                            'user_id' => $order->client->user_id,
                            'title' => 'M-Pesa Payment Unsuccessful',
                            'message' => "Your payment of " . number_format($payment->amount) . " KSH for order #NB-ORD-{$payment->order_id} was unsuccessful. Reason: " . ($payment->result_description ?? 'Declined by customer/provider') . ". Click to retry payment.",
                            'type' => 'warning',
                        ]);
                    }
                }
            }
        });
    }
}