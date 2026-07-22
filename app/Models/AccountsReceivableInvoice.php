<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountsReceivableInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'client_id',
        'amount_due',
        'amount_paid',
        'due_at',
        'status', // unpaid, partially_paid, paid, overdue
    ];

    protected $casts = [
        'amount_due' => 'integer',
        'amount_paid' => 'integer',
        'due_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(AccountsReceivablePayment::class, 'ar_invoice_id');
    }

    public function getBalanceDueAttribute(): int
    {
        return max(0, $this->amount_due - $this->amount_paid);
    }
}
