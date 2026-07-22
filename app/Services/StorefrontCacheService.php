<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class StorefrontCacheService
{
    public const TAG = 'storefront';

    /**
     * Remember data in cache using tags if supported by driver, with fallback for file/array stores.
     */
    public static function remember(string $key, int $ttl, \Closure $callback)
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
                return Cache::tags([self::TAG, 'products', 'curation'])->remember($key, $ttl, $callback);
            }
        } catch (\Throwable $e) {
            // Fallback to standard cache
        }

        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Flush all storefront, catalog, and curation cache tags/keys.
     */
    public static function flush(): void
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
                Cache::tags([self::TAG, 'products', 'curation'])->flush();
            }
        } catch (\Throwable $e) {
            // Ignore tag errors
        }

        Cache::forget('storefront_products_catalog');
        Cache::forget('storefront_total_count');
        Cache::forget('occasions_all');
        Cache::forget('curation_builder_stems');
        Cache::forget('curation_builder_wrappings');
        Cache::forget('curation_builder_mists');
        Cache::forget('curation_builder_wines');
        Cache::forget('curation_builder_chocolates');
        Cache::forget('curation_builder_jewelry');
        Cache::forget('curation_builder_ribbons');
        Cache::forget('curation_glitter_product');
        Cache::forget('curation_card_product');

        // Flush repository cache store to guarantee invalidation on file/array drivers
        Cache::flush();
    }
}
