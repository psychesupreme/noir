<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class StorefrontCacheService
{
    public const TAG = 'storefront';
    public const KNOWN_KEYS = 'storefront_registered_cache_keys';

    /**
     * Remember data in cache using tags if supported by driver, with registered fallback for file/database/array stores.
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

        // Track key for non-taggable drivers
        self::registerKey($key);

        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Track dynamic cache key so non-taggable stores can flush cleanly.
     */
    protected static function registerKey(string $key): void
    {
        try {
            $keys = Cache::get(self::KNOWN_KEYS, []);
            if (!in_array($key, $keys, true)) {
                $keys[] = $key;
                Cache::put(self::KNOWN_KEYS, $keys, 86400);
            }
        } catch (\Throwable $e) {
            // Suppress tracking failures
        }
    }

    /**
     * Flush all storefront, catalog, and curation cache tags/keys without nuking whole app cache.
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

        // Forget all dynamically registered keys
        try {
            $registeredKeys = Cache::get(self::KNOWN_KEYS, []);
            foreach ($registeredKeys as $k) {
                Cache::forget($k);
            }
            Cache::forget(self::KNOWN_KEYS);
        } catch (\Throwable $e) {
            // Ignore
        }

        // Static known catalog keys
        $staticKeys = [
            'storefront_products_catalog',
            'storefront_total_count',
            'storefront_offer_products',
            'occasions_all',
            'curation_builder_stems',
            'curation_builder_wrappings',
            'curation_builder_mists',
            'curation_builder_wines',
            'curation_builder_chocolates',
            'curation_builder_jewelry',
            'curation_builder_ribbons',
            'curation_glitter_product',
            'curation_card_product',
        ];

        foreach ($staticKeys as $k) {
            Cache::forget($k);
        }
    }
}
