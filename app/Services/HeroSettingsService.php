<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class HeroSettingsService
{
    protected static string $fileName = 'hero_settings.json';

    public static function getSlides(): array
    {
        if (!Storage::exists(self::$fileName)) {
            self::saveSlides(self::getDefaultSlides());
        }

        return json_decode(Storage::get(self::$fileName), true) ?: [];
    }

    public static function saveSlides(array $slides): void
    {
        Storage::put(self::$fileName, json_encode($slides, JSON_PRETTY_PRINT));
    }

    public static function getDefaultSlides(): array
    {
        return [
            [
                'badge' => 'Pure Rift Valley Luxury',
                'title' => 'Naivasha Volcanic Roses',
                'description' => 'Premium, long-stemmed Naomi red roses grown in the nutrient-dense volcanic soils of Lake Naivasha. Cut daily and shipped via cold chain.',
                'bg_image' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?auto=format&fit=crop&q=80&w=1200',
                'cta_text' => 'Order Stems Catalog',
                'cta_link' => 'stems'
            ],
            [
                'badge' => 'Highland Curation Trunks',
                'title' => 'Limuru Berry & Bloom',
                'description' => 'An elite combination of hand-tied fresh bouquets, artisanal organic berry infusions, and premium purple tea leaves sourced directly from misty Limuru growers.',
                'bg_image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=1200',
                'cta_text' => 'Acquire Curation Trunk',
                'cta_link' => 'hampers'
            ],
            [
                'badge' => 'Savannah Botanical Spray',
                'title' => 'Rift Valley Sunset',
                'description' => 'Vibrant lilies, golden sunflowers, and wild eucalyptus sprays, designed to embody the majestic warmth of the Great Rift Valley\'s golden hour.',
                'bg_image' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?auto=format&fit=crop&q=80&w=1200',
                'cta_text' => 'Acquire Arrangement',
                'cta_link' => 'bouquets'
            ],
            [
                'badge' => 'Corporate & Home Styling',
                'title' => 'Mt. Kenya Alabaster',
                'description' => 'Elite weekly orchid rotations, white lily suspensions, and tailored lobby installations designed for high-end workspaces and luxury private residences.',
                'bg_image' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=1200',
                'cta_text' => 'Reserve Installation',
                'cta_link' => 'specialization'
            ],
            [
                'badge' => 'Sun-Kissed Coastal Flora',
                'title' => 'Watamu Blue Hydrangeas',
                'description' => 'Stunning blue hydrangeas and custom coastal wraps reflecting the crystal turquoise waters and tropical breezes of the Watamu coast.',
                'bg_image' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=1200',
                'cta_text' => 'View Coastal Curation',
                'cta_link' => 'giftings'
            ]
        ];
    }
}
