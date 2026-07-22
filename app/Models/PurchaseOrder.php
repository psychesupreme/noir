<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'branch_id',
        'po_number',
        'status',
        'total_cost',
        'notes',
        'ordered_at',
        'received_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'received_at' => 'datetime',
        'total_cost' => 'integer',
    ];

    /**
     * Get the supplier vendor.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the destination branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the line items in this purchase order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Boot model events for auto-generating sequential PO numbers.
     */
    protected static function booted(): void
    {
        static::creating(function ($po) {
            if (empty($po->po_number)) {
                $prefix = 'PO-' . date('Ym') . '-';
                $lastPo = static::where('po_number', 'like', "{$prefix}%")
                    ->orderBy('id', 'desc')
                    ->first();
                
                $nextNum = 1;
                if ($lastPo) {
                    $parts = explode('-', $lastPo->po_number);
                    $lastNum = (int) end($parts);
                    $nextNum = $lastNum + 1;
                }
                
                $po->po_number = $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
