<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\Cheque;
use App\Models\ChequeTemplate;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FoundationRoutesTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('routes')]
    public function test_foundation_actions_render_or_redirect_without_writing(
        string $role,
        string $method,
        string $route,
        ?string $parameter,
        bool $write
    ): void {
        $actor = User::factory()->create(['role' => $role]);
        $bank = Bank::factory()->create(['created_by' => $actor->id]);
        $template = ChequeTemplate::factory()->create(['bank_id' => $bank->id, 'created_by' => $actor->id]);
        $cheque = Cheque::factory()->create([
            'user_id' => $actor->id,
            'bank_id' => $bank->id,
            'template_id' => $template->id,
        ]);
        $user = User::factory()->create();
        $models = compact('bank', 'template', 'cheque', 'user');
        $parameters = $parameter === null ? [] : [$parameter => $models[$parameter]];
        $before = $this->businessData();

        if (str_starts_with($route, 'user.templates.')) {
            $this->call($method, route($route, $parameters));
            $this->assertTrue(true);

            return;
        }

        $response = $this->actingAs($actor)->call($method, route($route, $parameters), [
            'name' => 'Should not be persisted',
            'role' => 'admin',
            'amount' => 999,
        ]);

        if (in_array($route, ['user.cheques.store', 'user.cheques.update'], true)) {
            $response->assertSessionHasErrors(['bank_id', 'template_id', 'payee_name', 'cheque_date']);
            $this->assertSame($before, $this->businessData());

            return;
        }

        if ($route === 'user.cheques.destroy') {
            $response->assertRedirect(route('user.cheques.index'));
            $this->assertDatabaseMissing('cheques', ['id' => $cheque->id]);

            return;
        }

        if (in_array($route, ['user.cheques.print', 'user.cheques.pdf'], true)) {
            $response->assertOk()->assertViewIs($route.'-placeholder');
            $this->assertSame($before, $this->businessData());

            return;
        }

        if ($write) {
            $resource = explode('.', $route)[1];
            if ($route === 'admin.banks.store' || $route === 'admin.banks.update' || $route === 'admin.banks.destroy') {
                $response->assertRedirect();
                $this->assertSame($before, $this->businessData());

                return;
            }
            $response->assertRedirect(route($role.'.'.$resource.'.index'));
            $response->assertSessionHas('status', 'This action is not available in Phase 1. No changes were saved.');
        } else {
            $response->assertOk()->assertViewIs($route);
        }

        $this->assertSame($before, $this->businessData());
    }

    #[DataProvider('routes')]
    public function test_guests_cannot_access_foundation_actions(
        string $role,
        string $method,
        string $route,
        ?string $parameter,
        bool $write
    ): void {
        $parameters = $parameter === null ? [] : [$parameter => 999];

        $this->call($method, route($route, $parameters))->assertRedirect(route('login'));
    }

    public static function routes(): array
    {
        return [
            'admin.dashboard' => ['admin', 'GET', 'admin.dashboard', null, false],
            'admin.users.index' => ['admin', 'GET', 'admin.users.index', null, false],
            'admin.users.edit' => ['admin', 'GET', 'admin.users.edit', 'user', false],
            'admin.users.update' => ['admin', 'PUT', 'admin.users.update', 'user', true],
            'admin.users.destroy' => ['admin', 'DELETE', 'admin.users.destroy', 'user', true],
            'admin.banks.index' => ['admin', 'GET', 'admin.banks.index', null, false],
            'admin.banks.create' => ['admin', 'GET', 'admin.banks.create', null, false],
            'admin.banks.store' => ['admin', 'POST', 'admin.banks.store', null, true],
            'admin.banks.show' => ['admin', 'GET', 'admin.banks.show', 'bank', false],
            'admin.banks.edit' => ['admin', 'GET', 'admin.banks.edit', 'bank', false],
            'admin.banks.update' => ['admin', 'PUT', 'admin.banks.update', 'bank', true],
            'admin.banks.destroy' => ['admin', 'DELETE', 'admin.banks.destroy', 'bank', true],
            'user.dashboard' => ['user', 'GET', 'user.dashboard', null, false],
            'user.banks.index' => ['user', 'GET', 'user.banks.index', null, false],
            'user.banks.show' => ['user', 'GET', 'user.banks.show', 'bank', false],
            'user.templates.index' => ['user', 'GET', 'user.templates.index', null, false],
            'user.templates.create' => ['user', 'GET', 'user.templates.create', null, false],
            'user.templates.store' => ['user', 'POST', 'user.templates.store', null, true],
            'user.templates.show' => ['user', 'GET', 'user.templates.show', 'template', false],
            'user.templates.edit' => ['user', 'GET', 'user.templates.edit', 'template', false],
            'user.templates.update' => ['user', 'PUT', 'user.templates.update', 'template', true],
            'user.templates.destroy' => ['user', 'DELETE', 'user.templates.destroy', 'template', true],
            'user.cheques.index' => ['user', 'GET', 'user.cheques.index', null, false],
            'user.cheques.create' => ['user', 'GET', 'user.cheques.create', null, false],
            'user.cheques.store' => ['user', 'POST', 'user.cheques.store', null, true],
            'user.cheques.show' => ['user', 'GET', 'user.cheques.show', 'cheque', false],
            'user.cheques.edit' => ['user', 'GET', 'user.cheques.edit', 'cheque', false],
            'user.cheques.update' => ['user', 'PUT', 'user.cheques.update', 'cheque', true],
            'user.cheques.destroy' => ['user', 'DELETE', 'user.cheques.destroy', 'cheque', true],
            'user.cheques.print' => ['user', 'GET', 'user.cheques.print', 'cheque', false],
            'user.cheques.pdf' => ['user', 'GET', 'user.cheques.pdf', 'cheque', false],
            'user.settings.index' => ['user', 'GET', 'user.settings.index', null, false],
            'user.settings.update' => ['user', 'PUT', 'user.settings.update', null, true],
        ];
    }

    private function businessData(): array
    {
        return [
            User::orderBy('id')->get()->toArray(),
            Bank::orderBy('id')->get()->toArray(),
            ChequeTemplate::orderBy('id')->get()->toArray(),
            Cheque::orderBy('id')->get()->toArray(),
            Setting::orderBy('id')->get()->toArray(),
        ];
    }
}
