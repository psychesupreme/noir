<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WastageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'product_id',
        'user_id',
        'quantity',
        'reason',
        'notes',
        'cost_estimate',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'cost_estimate' => 'integer',
    ];

    /**
     * Get the branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who logged the wastage.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Booted method to handle model events.
     */
    protected static function booted(): void
    {
        static::saved(function ($wastageLog) {
            \App\Services\StorefrontCacheService::flush();
        });

        static::deleted(function ($wastageLog) {
            \App\Services\StorefrontCacheService::flush();
        });
    }
}
