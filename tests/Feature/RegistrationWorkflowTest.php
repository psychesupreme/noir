<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_form_validates_required_fields(): void
    {
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->call('register')
            ->assertHasErrors(['name', 'email', 'phone_number', 'password']);
    }

    public function test_registration_form_validates_strict_rules(): void
    {
        // 1. Name must be letters/spaces only
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'John123')
            ->set('email', 'john@example.com')
            ->set('phone_number', '712345678')
            ->set('password', 'SecurePass123!')
            ->set('password_confirmation', 'SecurePass123!')
            ->call('register')
            ->assertHasErrors(['name']);

        // 2. Email must be strict format
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'John Doe')
            ->set('email', 'john#invalid@com')
            ->set('phone_number', '712345678')
            ->set('password', 'SecurePass123!')
            ->set('password_confirmation', 'SecurePass123!')
            ->call('register')
            ->assertHasErrors(['email']);

        // 3. Phone number must be 9 digits starting with 7 or 1
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone_number', '912345678') // starts with 9, invalid
            ->set('password', 'SecurePass123!')
            ->set('password_confirmation', 'SecurePass123!')
            ->call('register')
            ->assertHasErrors(['phone_number']);

        // 4. Password must be complex
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone_number', '712345678')
            ->set('password', 'weakpass')
            ->set('password_confirmation', 'weakpass')
            ->call('register')
            ->assertHasErrors(['password']);
    }

    public function test_registration_workflow_succeeds_with_valid_details(): void
    {
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'John Doe')
            ->set('email', 'john.doe@example.com')
            ->set('phone_number', '712345678')
            ->set('password', 'SecurePass123!')
            ->set('password_confirmation', 'SecurePass123!')
            ->set('account_type', 'retail')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'phone_number' => '712345678'
        ]);
    }

    public function test_social_login_workflow(): void
    {
        // 1. Test redirect endpoint returns the approval view (sandbox mock mode)
        $response = $this->get(route('social.redirect', ['provider' => 'google']));
        $response->assertStatus(200);
        $response->assertViewIs('auth.social.approval');

        // 2. Test callback endpoint registers and logs in the mock user
        $callbackResponse = $this->get(route('social.callback', ['provider' => 'google', 'simulated' => '1']));
        $callbackResponse->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'name' => 'Google Curation Member',
            'email' => 'google.user@noirbloom.com'
        ]);
        
        $this->assertAuthenticated();
    }
}
