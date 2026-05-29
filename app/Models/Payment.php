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
    }
}