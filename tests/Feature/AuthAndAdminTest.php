<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_storefront(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_from_admin_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_guest_can_see_login_and_register_pages(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'account_tier' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_staff_can_access_admin_dashboard(): void
    {
        $staff = User::factory()->create([
            'account_tier' => UserRole::Staff,
        ]);

        $response = $this->actingAs($staff)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_retail_user_cannot_access_admin_dashboard(): void
    {
        $retailUser = User::factory()->create([
            'account_tier' => UserRole::Retail,
        ]);

        $response = $this->actingAs($retailUser)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_wholesale_user_cannot_access_admin_dashboard(): void
    {
        $wholesaleUser = User::factory()->create([
            'account_tier' => UserRole::Wholesale,
        ]);

        $response = $this->actingAs($wholesaleUser)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_branches_page(): void
    {
        $admin = User::factory()->create([
            'account_tier' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/branches');
        $response->assertStatus(200);
    }
}

