<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoAndSocialMetadataTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_contains_seo_and_open_graph_meta_tags(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Assert description is present
        $response->assertSee('meta name="description"', false);
        $response->assertSee('Explore Noir & Bloom\'s premium floral curations', false);

        // Assert Open Graph tags are present
        $response->assertSee('property="og:title" content="Noir &amp; Bloom | Premium Floral Curations &amp; Luxury Gifting Atelier"', false);
        $response->assertSee('property="og:type" content="website"', false);

        // Assert Twitter Card tags are present
        $response->assertSee('name="twitter:card" content="summary_large_image"', false);
        $response->assertSee('name="twitter:site" content="@NoirAndBloom"', false);

        // Assert JSON-LD is present
        $response->assertSee('"@type": "Organization"', false);
        $response->assertSee('"name": "Noir & Bloom"', false);

        // Assert Outfit Google Font is loaded
        $response->assertSee('fonts.bunny.net/css?family=instrument-serif:400,400i|plus-jakarta-sans:300,400,500|outfit:300,400,600,700', false);

        // Assert single h1 tag is present for screen readers / crawlers
        $response->assertSee('class="sr-only">Noir &amp; Bloom | Premium Floral Curations &amp; Luxury Gifting Atelier</h1>', false);
    }

    public function test_services_page_contains_specific_seo_and_business_schemas(): void
    {
        $response = $this->get('/services-gifts');
        $response->assertStatus(200);

        // Assert dynamic page title and SMM tags
        $response->assertSee('property="og:title" content="Bespoke Services &amp; Luxury Gifting Suites | Noir &amp; Bloom"', false);

        // Assert LocalBusiness Schema JSON-LD is loaded
        $response->assertSee('"@type": "LocalBusiness"', false);
        $response->assertSee('Noir &amp; Bloom - Nairobi Atelier', false);
        $response->assertSee('Noir &amp; Bloom - Kiambu Atelier', false);

        // Assert Single h1 tag
        $response->assertSee('Services &amp; Gifting Accents', false);
    }

    public function test_profile_portal_is_not_indexed_by_search_engines(): void
    {
        $user = User::factory()->create([
            'account_tier' => UserRole::Retail,
        ]);

        $response = $this->actingAs($user)->get('/profile-portal');
        $response->assertStatus(200);

        // Assert robots meta tags block search engine crawls on personal portal logs
        $response->assertSee('name="robots" content="noindex, nofollow"', false);
    }
}
