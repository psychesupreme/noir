<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Services\Socialite\AppleProvider;
use App\Services\Socialite\MicrosoftProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialOAuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_socialite_custom_drivers_are_registered_correctly()
    {
        $appleDriver = Socialite::driver('apple');
        $microsoftDriver = Socialite::driver('microsoft');

        $this->assertInstanceOf(AppleProvider::class, $appleDriver);
        $this->assertInstanceOf(MicrosoftProvider::class, $microsoftDriver);
    }

    public function test_social_callback_route_supports_both_get_and_post()
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

        // 3. Mock Socialite driver for POST request
        $abstractUserPost = $this->createMock(\Laravel\Socialite\Two\User::class);
        $abstractUserPost->method('getEmail')->willReturn('oauth-post@test.com');
        $abstractUserPost->method('getName')->willReturn('OAuth POST User');

        $providerMockPost = $this->getMockBuilder(\Laravel\Socialite\Two\AbstractProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $providerMockPost->method('user')->willReturn($abstractUserPost);

        Socialite::shouldReceive('driver')->with('apple')->andReturn($providerMockPost);

        // Test POST request to callback (simulate CSRF exempt POST callback like Apple)
        $postResponse = $this->post('/auth/social/apple/callback');
        $postResponse->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'oauth-post@test.com',
            'name' => 'OAuth POST User'
        ]);
    }
}
