<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialOAuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_social_callback_route_works()
    {
        // Use simulated approval flow which doesn't require real Socialite driver
        $response = $this->get('/auth/social/google/callback?simulated=1');
        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'google.user@noirbloom.com',
            'name' => 'Google Curation Member',
        ]);
    }
}
