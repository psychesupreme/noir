<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtimsInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'internal_invoice_number',
        'cu_invoice_number',
        'gross_amount',
        'taxable_amount',
        'vat_amount',
        'status',
        'kra_qr_url',
        'error_log_payload'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}