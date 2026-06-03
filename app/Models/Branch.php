<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'location_city', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all corporate orders assigned to this fulfillment branch hub node.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all product stock records in this branch.
     */
    public function productStocks(): HasMany
    {
        return $this->hasMany(BranchProductStock::class);
    }

    /**
     * Get purchase orders assigned to this branch.
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Get wastage logs registered at this branch.
     */
    public function wastageLogs(): HasMany
    {
        return $this->hasMany(WastageLog::class);
    }

    /**
     * Get stock level for a specific product at this branch.
     */
    public function getStockForProduct(int $productId): int
    {
        return $this->productStocks()->where('product_id', $productId)->value('stock') ?: 0;
    }

    /**
     * Get total retail stock value (stock * product.price) across all items in this branch.
     */
    public function getTotalStockValueAttribute(): int
    {
        return $this->productStocks()
            ->join('products', 'branch_product_stock.product_id', '=', 'products.id')
            ->sum(\DB::raw('branch_product_stock.stock * products.price'));
    }
}