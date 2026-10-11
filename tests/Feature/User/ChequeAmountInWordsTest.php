<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\Bank;
use App\Models\Cheque;
use App\Models\ChequeTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChequeAmountInWordsTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_line_endpoint_returns_only_the_full_english_text(): void
    {
        [$user, $template] = $this->templateWithPositions(['amount_words_1' => []]);

        $this->actingAs($user)
            ->getJson(route('user.cheques.amount-in-words', ['amount' => 1250.50, 'template_id' => $template->id]))
            ->assertExactJson([
                'full' => 'One Thousand Two Hundred Fifty Taka and Fifty Paisa Only',
                'line_1' => null,
                'line_2' => null,
            ]);
    }

    public function test_two_line_endpoint_and_saved_cheque_use_the_english_split(): void
    {
        [$user, $template, $bank] = $this->templateWithPositions([
            'amount_words_1' => [],
            'amount_words_2' => [],
        ]);

        $json = $this->actingAs($user)
            ->getJson(route('user.cheques.amount-in-words', ['amount' => 12345678.90, 'template_id' => $template->id]))
            ->assertOk()
            ->json();

        $this->assertSame('One Crore Twenty Three Lakh Forty Five Thousand Six Hundred Seventy Eight Taka and Ninety Paisa Only', $json['full']);
        $this->assertNotEmpty($json['line_1']);
        $this->assertNotEmpty($json['line_2']);

        $this->post(route('user.cheques.store'), [
            'bank_id' => $bank->id,
            'template_id' => $template->id,
            'payee_name' => 'Example Supplier',
            'amount' => 12345678.90,
            'cheque_date' => '2026-10-11',
        ])->assertRedirect();

        $cheque = Cheque::firstOrFail();
        $this->assertSame($json['full'], $cheque->amount_in_words);
        $this->assertSame($json['line_1'], $cheque->amount_words_line_1);
        $this->assertSame($json['line_2'], $cheque->amount_words_line_2);
    }

    public function test_cheque_pages_render_formatted_digits_without_a_currency_prefix(): void
    {
        [$user, $template, $bank] = $this->templateWithPositions([]);
        $cheque = Cheque::factory()->create([
            'user_id' => $user->id,
            'bank_id' => $bank->id,
            'template_id' => $template->id,
            'amount' => 4556222,
            'amount_in_words' => 'Forty Five Lakh Fifty Six Thousand Two Hundred Twenty Two Taka Only',
        ]);

        $this->assertSame('4,556,222.00', $cheque->formatted_amount);

        $this->actingAs($user)
            ->get(route('user.cheques.index'))
            ->assertOk()
            ->assertSee('4,556,222.00')
            ->assertDontSee('BDT 4,556,222.00');

        $this->get(route('user.cheques.show', $cheque))
            ->assertOk()
            ->assertSee('4,556,222.00')
            ->assertDontSee('BDT 4,556,222.00')
            ->assertSee('Taka Only');
    }

    private function templateWithPositions(array $positions): array
    {
        $user = User::factory()->create(['role' => 'user']);
        $bank = Bank::factory()->create();
        $template = ChequeTemplate::factory()->create([
            'bank_id' => $bank->id,
            'created_by' => $user->id,
            'field_positions' => $positions,
        ]);

        return [$user, $template, $bank];
    }
}
