<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchProductStock extends Model
{
    use HasFactory;

    protected $table = 'branch_product_stock';

    protected $fillable = [
        'branch_id',
        'product_id',
        'stock',
        'min_stock_level',
    ];

    protected $casts = [
        'stock' => 'integer',
        'min_stock_level' => 'integer',
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
     * Booted method to handle model events.
     */
    protected static function booted(): void
    {
        static::saved(function ($branchStock) {
            if ($branchStock->product) {
                $branchStock->product->syncGlobalStock();
            }
        });

        static::deleted(function ($branchStock) {
            if ($branchStock->product) {
                $branchStock->product->syncGlobalStock();
            }
        });
    }
}
