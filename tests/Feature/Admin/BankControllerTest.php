<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Bank;
use App\Models\Cheque;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BankControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_index_and_guests_or_users_cannot(): void
    {
        Bank::factory()->count(2)->create();
        $this->get(route('admin.banks.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.banks.index'))->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get(route('admin.banks.index'))->assertOk();
    }

    public function test_admin_can_create_update_search_and_paginate_banks(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->post(route('admin.banks.store'), [
            'name' => 'Evidence Bank', 'country' => 'Bangladesh',
            'logo' => UploadedFile::fake()->image('logo.png'), 'is_default' => '1',
        ])->assertRedirect();
        $bank = Bank::where('name', 'Evidence Bank')->firstOrFail();
        Storage::disk('public')->assertExists($bank->logo_path);
        $old = $bank->logo_path;
        $this->actingAs($admin)->put(route('admin.banks.update', $bank), [
            'name' => 'Updated Bank', 'country' => 'India',
            'logo' => UploadedFile::fake()->image('new.png'),
        ])->assertRedirect();
        Storage::disk('public')->assertMissing($old);
        $this->assertDatabaseHas('banks', ['name' => 'Updated Bank', 'country' => 'India']);
        Bank::factory()->count(16)->create();
        $this->actingAs($admin)->get(route('admin.banks.index', ['q' => 'Updated Bank']))->assertSee('Updated Bank');
        $this->actingAs($admin)->get(route('admin.banks.index'))->assertSee('page=2');
    }

    public function test_unique_validation_and_delete_protection_and_default_toggle(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $first = Bank::factory()->create(['is_default' => true]);
        $second = Bank::factory()->create();
        $this->actingAs($admin)->post(route('admin.banks.store'), ['name' => $first->name, 'country' => 'Bangladesh'])
            ->assertSessionHasErrors('name');
        $this->actingAs($admin)->post(route('admin.banks.toggle-default', $second))->assertRedirect();
        $this->assertTrue($second->refresh()->is_default);
        $this->assertFalse($first->refresh()->is_default);
        Cheque::factory()->create(['bank_id' => $second->id]);
        $this->actingAs($admin)->delete(route('admin.banks.destroy', $second))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('banks', ['id' => $second->id]);
    }

    public function test_admin_can_delete_unused_bank(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $bank = Bank::factory()->create(['logo_path' => UploadedFile::fake()->image('logo.png')->store('uploads/bank-logos', 'public')]);
        $path = $bank->logo_path;
        $this->actingAs($admin)->delete(route('admin.banks.destroy', $bank))->assertRedirect(route('admin.banks.index'));
        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseMissing('banks', ['id' => $bank->id]);
    }

    public function test_logo_accessor_and_bank_pages_use_public_logo_url(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $path = UploadedFile::fake()->image('logo.png')->store('uploads/bank-logos', 'public');
        $bank = Bank::factory()->create(['logo_path' => $path]);

        Storage::disk('public')->assertExists($path);
        $this->assertSame(Storage::disk('public')->url($path), $bank->logo_url);
        $this->actingAs($admin)->get(route('admin.banks.index'))
            ->assertOk()->assertSee($bank->logo_url, false);
        $this->actingAs($admin)->get(route('admin.banks.show', $bank))
            ->assertOk()->assertSee($bank->logo_url, false);
    }
}
