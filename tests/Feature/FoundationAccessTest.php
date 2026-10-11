<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FoundationAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_is_only_accessible_to_admins(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get('/admin/dashboard')->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get('/admin/dashboard')->assertOk()->assertSee('Admin Dashboard');
    }

    public function test_user_dashboard_requires_authentication(): void
    {
        $this->get('/user/dashboard')->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get('/user/dashboard')->assertOk();
    }

    #[DataProvider('roles')]
    public function test_login_and_dashboard_redirect_by_role(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route($role.'.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->get('/dashboard')->assertRedirect(route($role.'.dashboard'));
    }

    public static function roles(): array
    {
        return [['admin'], ['user']];
    }

    public function test_login_uses_role_dashboard_even_after_an_intended_admin_url(): void
    {
        $user = User::factory()->create();

        $this->withSession(['url.intended' => url('/admin/dashboard')])
            ->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('user.dashboard'));
    }

    public function test_inactive_accounts_cannot_log_in(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    #[DataProvider('roles')]
    public function test_inactive_sessions_are_logged_out(string $role): void
    {
        $user = User::factory()->create(['role' => $role, 'is_active' => false]);

        $this->actingAs($user)->get('/'.$role.'/dashboard')
            ->assertRedirect(route('login'))->assertSessionHas('status');
        $this->assertGuest();
    }

    public function test_registration_cannot_set_privileged_fields(): void
    {
        $this->post('/register', [
            'name' => 'Example User',
            'email' => 'safe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'is_active' => false,
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'safe@example.com',
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    public function test_invalid_login_input_is_validated(): void
    {
        $this->post('/login', ['email' => ['invalid'], 'password' => ['invalid']])
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_application_routes_are_named(): void
    {
        foreach (app('router')->getRoutes() as $route) {
            if ($route->uri() === 'up') {
                continue;
            }

            $this->assertNotEmpty($route->getName(), $route->uri());
        }
    }
}
