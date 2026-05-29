<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    public ?string $adjustment_reason = null;

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
     * Booted method to handle eloquent model events.
     */
    protected static function booted(): void
    {
        static::updating(function ($product) {
            if ($product->isDirty('stock')) {
                $original = (int) $product->getOriginal('stock');
                $current = (int) $product->stock;
                $diff = $current - $original;

                // Log details to database
                \App\Models\InventoryLog::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'quantity_before' => $original,
                    'quantity_after' => $current,
                    'adjustment' => $diff,
                    'reason' => $product->adjustment_reason ?: 'System adjustment',
                ]);
            }
        });

        static::created(function ($product) {
            if ($product->stock > 0) {
                \App\Models\InventoryLog::create([
                    'product_id' => $product->id,
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