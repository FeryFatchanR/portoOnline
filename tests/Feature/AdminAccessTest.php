<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/skills')->assertRedirect('/admin/login');
    }

    public function test_login_page_is_available(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_non_admin_user_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->get('/admin/skills')->assertForbidden();
    }

    public function test_admin_can_open_every_admin_page(): void
    {
        $admin = User::factory()->admin()->create();

        foreach (['/admin', '/admin/profile', '/admin/stats', '/admin/skills', '/admin/projects', '/admin/services', '/admin/team-members', '/admin/experiences',
            '/admin/organizations', '/admin/education', '/admin/certifications'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_is_admin_cannot_be_mass_assigned(): void
    {
        $user = User::create([
            'name' => 'X', 'email' => 'x@example.com', 'password' => 'secret-password', 'is_admin' => true,
        ]);

        $this->assertFalse($user->fresh()->is_admin);
    }

    public function test_create_admin_command_promotes_existing_user(): void
    {
        $user = User::factory()->create(['email' => 'owner@example.com']);

        $this->artisan('app:create-admin', ['--email' => 'owner@example.com'])->assertSuccessful();

        $this->assertTrue($user->fresh()->is_admin);
    }
}
