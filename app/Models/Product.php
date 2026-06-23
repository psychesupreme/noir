<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public ?string $adjustment_reason = null;
    public ?int $adjustment_branch_id = null;

    /**
     * The attributes that are mass assignable.
     * Tailored for the Noir & Bloom luxury catalog.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'cost_price',
        'stock',
        'category',
        'unit_type',
        'grade',
        'image_url',
    ];

    /**
     * The attributes that should be cast.
     * Ensures currency calculations preserve mathematical integrity.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'integer',
        'cost_price' => 'integer',
        'stock' => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────

    /**
     * The luxury events, holidays, and design themes associated with this arrangement.
     * Establishes the Many-to-Many relationship with the Occasions catalog.
     */
    public function occasions(): BelongsToMany
    {
        return $this->belongsToMany(Occasion::class);
    }

    /**
     * Get the orders associated with this product.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price_at_sale')->withTimestamps();
    }


    /**
     * Get branch-specific stock levels for this product.
     */
    public function branchStocks(): HasMany
    {
        return $this->hasMany(BranchProductStock::class);
    }

    /**
     * Get purchase order line items for this product.
     */
    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Get wastage logs for this product.
     */
    public function wastageLogs(): HasMany
    {
        return $this->hasMany(WastageLog::class);
    }

    /**
     * Get reviews for this product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get average rating.
     */
    public function getAverageRatingAttribute(): float
    {
        return (float) round($this->reviews()->avg('rating') ?: 0, 1);
    }

    public function getAverageQualityRatingAttribute(): float
    {
        return (float) round($this->reviews()->avg('quality_rating') ?: 0, 1);
    }

    public function getAverageFreshnessRatingAttribute(): float
    {
        return (float) round($this->reviews()->avg('freshness_rating') ?: 0, 1);
    }

    public function getAverageValueRatingAttribute(): float
    {
        return (float) round($this->reviews()->avg('value_rating') ?: 0, 1);
    }

    // ── Query Scopes ─────────────────────────────────────────────────────

    /**
     * Filter products by catalog category (retail, wholesale, gifting).
     */
    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Filter products with dangerously low inventory levels.
     * Default threshold: 10 units.
     */
    public function scopeLowStock(Builder $query, int $threshold = 10): Builder
    {
        return $query->where('stock', '<=', $threshold);
    }

    // ── Accessors ────────────────────────────────────────────────────────

    /**
     * Display price in Kenyan Shillings with proper formatting.
     * Example: "Ksh 12,500"
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Ksh ' . number_format($this->price);
    }

    /**
     * Display cost price in Kenyan Shillings with proper formatting.
     */
    public function getFormattedCostPriceAttribute(): string
    {
        return 'Ksh ' . number_format($this->cost_price);
    }

    /**
     * Get the gross margin in KSH.
     */
    public function getGrossMarginAttribute(): int
    {
        return $this->price - $this->cost_price;
    }

    /**
     * Get the margin percentage.
     */
    public function getMarginPercentAttribute(): float
    {
        if ($this->price <= 0) {
            return 0;
        }
        return round(($this->getGrossMarginAttribute() / $this->price) * 100, 2);
    }

    /**
     * Sync global stock field from branch-level stock records.
     */
    public function syncGlobalStock(): void
    {
        $totalStock = $this->branchStocks()->sum('stock');
        static::withoutEvents(function () use ($totalStock) {
            $this->update(['stock' => $totalStock]);
        });
    }

    /**
     * Generate automatic SKU based on branch region and category.
     */
    public static function generateSkuForProduct(Product $product): string
    {
        $branchId = $product->adjustment_branch_id ?: (
            \App\Models\Branch::where('is_active', true)->value('id') ?: \App\Models\Branch::value('id')
        );

        $branch = \App\Models\Branch::find($branchId);
        $branchCode = 'NRB'; // default fallback

        if ($branch) {
            $source = $branch->location_city ?: $branch->name;
            $lowerSource = strtolower($source);
            if (str_contains($lowerSource, 'nairobi')) {
                $branchCode = 'NRB';
            } elseif (str_contains($lowerSource, 'kiambu')) {
                $branchCode = 'KMB';
            } else {
                // Dynamic consonant extraction for new hubs/regions
                $clean = preg_replace('/[^a-zA-Z]/', '', $source);
                $chars = str_split(strtoupper($clean));
                $consonants = [];
                $vowels = ['A','E','I','O','U'];
                foreach ($chars as $c) {
                    if (!in_array($c, $vowels) && !in_array($c, $consonants)) {
                        $consonants[] = $c;
                    }
                }
                if (count($consonants) < 3) {
                    foreach ($chars as $c) {
                        if (in_array($c, $vowels) && !in_array($c, $consonants) && count($consonants) < 3) {
                            $consonants[] = $c;
                        }
                    }
                }
                $branchCode = count($consonants) >= 3 
                    ? implode('', array_slice($consonants, 0, 3)) 
                    : str_pad(implode('', $consonants), 3, 'X');
            }
        }

        $categoryLetter = match (strtolower($product->category ?: 'stems')) {
            'stems' => 'S',
            'bouquet', 'bouquets' => 'B',
            'bundle' => 'U',
            'giftings', 'gifting', 'hampers' => 'G',
            'home_decor', 'decor' => 'D',
            'specializtion', 'specialization', 'specializations' => 'P',
            'retail' => 'R',
            'wholesale' => 'W',
            default => 'U',
        };

        $prefix = $branchCode . '-' . $categoryLetter . '-';
        
        // Find the next sequence number
        $lastProduct = static::where('sku', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastProduct) {
            $parts = explode('-', $lastProduct->sku);
            $lastNum = (int) end($parts);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Booted method to handle eloquent model events.
     */
    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = static::generateSkuForProduct($product);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('stock')) {
                $original = (int) $product->getOriginal('stock');
                $current = (int) $product->stock;
                $diff = $current - $original;

                $branchId = $product->adjustment_branch_id ?: (
                    \App\Models\Branch::where('is_active', true)->value('id') ?: \App\Models\Branch::value('id')
                );

                if ($branchId) {
                    $branchStock = \App\Models\BranchProductStock::firstOrCreate(
                        ['branch_id' => $branchId, 'product_id' => $product->id],
                        ['stock' => 0]
                    );

                    \App\Models\BranchProductStock::withoutEvents(function () use ($branchStock, $diff) {
                        $branchStock->stock = max(0, $branchStock->stock + $diff);
                        $branchStock->save();
                    });
                }

                // Log details to database
                \App\Models\InventoryLog::create([
                    'product_id' => $product->id,
                    'branch_id' => $branchId,
                    'user_id' => auth()->id(),
                    'quantity_before' => $original,
                    'quantity_after' => $current,
                    'adjustment' => $diff,
                    'reason' => $product->adjustment_reason ?: 'System adjustment',
                ]);
            }
        });

        static::created(function ($product) {
            $branchId = $product->adjustment_branch_id ?: (
                \App\Models\Branch::where('is_active', true)->value('id') ?: \App\Models\Branch::value('id')
            );

            if ($product->stock > 0 && $branchId) {
                \App\Models\BranchProductStock::create([
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                    'stock' => $product->stock,
                    'min_stock_level' => 5,
                ]);

                \App\Models\InventoryLog::create([
                    'product_id' => $product->id,
                    'branch_id' => $branchId,
                    'user_id' => auth()->id(),
                    'quantity_before' => 0,
                    'quantity_after' => $product->stock,
                    'adjustment' => $product->stock,
                    'reason' => 'Initial catalog creation',
                ]);
            }
        });

        static::saved(function ($product) {
            \Illuminate\Support\Facades\Cache::forget('storefront_products_base');
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });

        static::deleted(function ($product) {
            \Illuminate\Support\Facades\Cache::forget('storefront_products_base');
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });
    }
}