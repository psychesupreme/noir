<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialOAuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_social_callback_route_works()
    {
        // 1. Mock Socialite driver for GET request
        $abstractUserGet = $this->createMock(\Laravel\Socialite\Two\User::class);
        $abstractUserGet->method('getEmail')->willReturn('oauth-get@test.com');
        $abstractUserGet->method('getName')->willReturn('OAuth GET User');

        $providerMockGet = $this->getMockBuilder(\Laravel\Socialite\Two\AbstractProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $providerMockGet->method('user')->willReturn($abstractUserGet);

        Socialite::shouldReceive('driver')->with('google')->andReturn($providerMockGet);

        // 2. Test GET request to callback
        $getResponse = $this->get('/auth/social/google/callback');
        $getResponse->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'oauth-get@test.com',
            'name' => 'OAuth GET User'
        ]);
    }
}
