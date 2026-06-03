<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountsReceivablePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ar_invoice_id',
        'amount',
        'payment_method',
        'reference_number',
        'recorded_at',
        'recorded_by_user_id',
    ];

    protected $casts = [
        'amount' => 'integer',
        'recorded_at' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(AccountsReceivableInvoice::class, 'ar_invoice_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }
}
