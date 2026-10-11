<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\Bank;
use App\Models\Cheque;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChequeDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_their_own_draft_cheque(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $cheque = Cheque::factory()->create(['user_id' => $user->id, 'status' => 'draft']);

        $response = $this->actingAs($user)->delete(route('user.cheques.destroy', $cheque));

        $response->assertRedirect(route('user.cheques.index'))->assertSessionHas('success', 'Cheque deleted successfully.');
        $this->assertDatabaseMissing('cheques', ['id' => $cheque->id]);
    }

    public function test_user_cannot_delete_a_printed_cheque(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $cheque = Cheque::factory()->create(['user_id' => $user->id, 'status' => 'printed']);

        $this->actingAs($user)->delete(route('user.cheques.destroy', $cheque))->assertForbidden();
        $this->assertDatabaseHas('cheques', ['id' => $cheque->id]);
    }

    public function test_user_cannot_delete_another_users_cheque(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $other = User::factory()->create(['role' => 'user']);
        $cheque = Cheque::factory()->create(['user_id' => $owner->id, 'status' => 'draft']);

        $this->actingAs($other)->delete(route('user.cheques.destroy', $cheque))->assertForbidden();
        $this->assertDatabaseHas('cheques', ['id' => $cheque->id]);
    }
}
