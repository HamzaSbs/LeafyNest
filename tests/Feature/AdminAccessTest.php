<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admins_are_redirected_to_login_from_admin_area(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get('/admin/plants')
            ->assertRedirect(route('admin.login'));
    }

    public function test_guests_are_redirected_to_login_from_admin_area(): void
    {
        $this->get('/admin/plants')
            ->assertRedirect(route('admin.login'));
    }

    public function test_admins_can_view_the_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('Admin Dashboard');
    }

    public function test_admin_dashboard_route_is_available_at_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee('Admin Dashboard');
    }
}
