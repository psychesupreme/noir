<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'quality_rating',
        'freshness_rating',
        'value_rating',
        'comment'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::saved(function ($review) {
            $productId = $review->product_id;
            \Illuminate\Support\Facades\Cache::forget("product_avg_rating_{$productId}");
            \Illuminate\Support\Facades\Cache::forget("product_avg_quality_rating_{$productId}");
            \Illuminate\Support\Facades\Cache::forget("product_avg_freshness_rating_{$productId}");
            \Illuminate\Support\Facades\Cache::forget("product_avg_value_rating_{$productId}");
            \Illuminate\Support\Facades\Cache::forget('storefront_products_base');
        });

        static::deleted(function ($review) {
            $productId = $review->product_id;
            \Illuminate\Support\Facades\Cache::forget("product_avg_rating_{$productId}");
            \Illuminate\Support\Facades\Cache::forget("product_avg_quality_rating_{$productId}");
            \Illuminate\Support\Facades\Cache::forget("product_avg_freshness_rating_{$productId}");
            \Illuminate\Support\Facades\Cache::forget("product_avg_value_rating_{$productId}");
            \Illuminate\Support\Facades\Cache::forget('storefront_products_base');
        });
    }
}
