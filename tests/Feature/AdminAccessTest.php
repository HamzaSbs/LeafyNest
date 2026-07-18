<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    public function test_non_admins_are_redirected_from_admin_area(): void
    {
        $this->get('/admin/plants')
            ->assertRedirect('/home');
    }

    public function test_admins_can_view_the_admin_dashboard(): void
    {
        $this->withSession(['user_role' => 'admin'])
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('Admin Dashboard')
            ->assertSee('Total Plants');
    }

    public function test_admin_dashboard_route_is_available_at_admin_dashboard(): void
    {
        $this->withSession(['user_role' => 'admin'])
            ->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee('Admin Dashboard');
    }
}
