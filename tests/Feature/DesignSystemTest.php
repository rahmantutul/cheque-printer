<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class DesignSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_user_dashboards_have_distinct_navigation_and_cards(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk()
            ->assertSee('bg-admin')->assertSee('ADMIN')->assertSee('Total Users')
            ->assertSee('Total Banks')->assertSee('Total Templates')->assertSee('Total Cheques')
            ->assertSee('Coming in a later phase')->assertSee('Skip to content');

        $this->actingAs(User::factory()->create())->get('/user/dashboard')->assertOk()
            ->assertSee('bg-user')->assertSee('USER')->assertSee('My Cheques')
            ->assertSee('Drafts')->assertSee('Printed')->assertSee('Templates')
            ->assertSee('My Banks')->assertSee('Settings')->assertDontSee('Total Users');
    }

    public function test_login_success_flash_is_escaped_and_displayed(): void
    {
        $user = User::factory()->create(['name' => '<script>alert(1)</script>']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHas('success', 'Welcome back, '.$user->name);

        $this->get('/user/dashboard')->assertSee('Welcome back, '.$user->name)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_logout_flash_appears_on_home(): void
    {
        $this->actingAs(User::factory()->create())->post('/logout')
            ->assertRedirect('/')->assertSessionHas('info', 'You have been logged out');
        $this->get('/')->assertOk()->assertSee('You have been logged out');
    }

    public function test_inactive_login_has_an_error_flash_without_authenticating(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/login')->assertSessionHas('error', 'Your account is inactive');
        $this->assertGuest();
        $this->get('/login')->assertOk()->assertSee('Your account is inactive');
    }

    public function test_inactive_status_is_not_disclosed_for_wrong_passwords(): void
    {
        $user = User::factory()->create(['is_active' => false]);
        $this->post('/login', ['email' => $user->email, 'password' => 'incorrect'])
            ->assertSessionMissing('error')->assertSessionHasErrors('email');
    }

    public function test_remember_checkbox_value_is_accepted(): void
    {
        $user = User::factory()->create();
        $this->post('/login', ['email' => $user->email, 'password' => 'password', 'remember' => '1'])
            ->assertSessionHasNoErrors()->assertRedirect(route('user.dashboard'));
    }

    public function test_custom_forbidden_and_missing_pages_render(): void
    {
        config(['app.debug' => false]);
        $this->actingAs(User::factory()->create())->get('/admin/dashboard')
            ->assertForbidden()->assertSee('Access Denied')->assertSee('Back to dashboard');
        $this->get('/nonexistent')->assertNotFound()->assertSee('Page not found');
    }

    public function test_standalone_error_views_render_without_an_authenticated_user(): void
    {
        foreach ([403, 404, 419, 429, 500, 503] as $status) {
            $this->view('errors.'.$status)->assertSee((string) $status);
        }
    }

    public function test_all_flash_types_are_visible_and_escaped(): void
    {
        $this->actingAs(User::factory()->create())->withSession([
            'success' => 'Saved <unsafe>',
            'error' => 'Failed <unsafe>',
            'warning' => 'Warning <unsafe>',
            'info' => 'Info <unsafe>',
        ])->get('/user/dashboard')->assertSee('Saved <unsafe>')->assertSee('Failed <unsafe>')
            ->assertSee('Warning <unsafe>')->assertSee('Info <unsafe>')
            ->assertDontSee('<unsafe>', false);
    }

    public function test_reusable_components_render_their_slots_and_accessible_controls(): void
    {
        view()->share('errors', new ViewErrorBag);

        $html = Blade::render(<<<'BLADE'
            <x-input name="payee" label="Payee" value="Example" help="Enter a name" />
            <x-select name="bank" label="Bank" :options="[1 => 'Bank One']" :selected="1" />
            <x-textarea name="memo" label="Memo" value="Notes" />
            <x-checkbox name="active" label="Active" :value="true" />
            <x-btn-primary href="/example" :disabled="true">Disabled</x-btn-primary>
            <x-btn-secondary>Secondary</x-btn-secondary>
            <x-btn-danger>Danger</x-btn-danger>
            <x-btn-ghost>Ghost</x-btn-ghost>
            <x-card title="Card"><p>Body</p><x-slot:footer>Footer</x-slot:footer></x-card>
            <x-empty-state title="No entries" description="Add an entry." icon="document-text">
                <x-slot:action><x-btn-primary>Add</x-btn-primary></x-slot:action>
            </x-empty-state>
            <x-badge color="success">Ready</x-badge>
            <x-table><x-slot:thead><tr><th scope="col">Name</th></tr></x-slot:thead><x-slot:tbody><tr><td>Entry</td></tr></x-slot:tbody></x-table>
            <x-confirm-modal />
            BLADE);

        $this->assertStringContainsString('for="payee"', $html);
        $this->assertStringContainsString('aria-describedby="payee-help"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringNotContainsString('href="/example"', $html);
        $this->assertStringContainsString('Footer', $html);
        $this->assertStringContainsString('selected', $html);
        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('<dialog', $html);
    }
}
