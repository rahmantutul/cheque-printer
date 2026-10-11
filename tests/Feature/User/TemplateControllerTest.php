<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\Bank;
use App\Models\ChequeTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TemplateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_default_dimensions_and_positions_are_saved(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $bank = Bank::factory()->create();
        $positions = (new ChequeTemplate)->defaultFieldPositions();
        $positions['payee']['width'] = 70;
        $positions['payee']['height'] = 9;
        $response = $this->actingAs($user)->post(route('user.templates.store'), [
            'bank_id' => $bank->id, 'name' => 'Resized layout',
            'background_image' => UploadedFile::fake()->image('cheque.png'),
            'width_mm' => 203, 'height_mm' => 90,
            'field_positions' => json_encode($positions),
        ]);
        $response->assertRedirect();
        $template = ChequeTemplate::where('name', 'Resized layout')->firstOrFail();
        $this->assertSame(70, $template->field_positions['payee']['width']);
        $this->assertSame(9, $template->field_positions['payee']['height']);
        $this->assertSame(70, $template->refresh()->field_positions['payee']['width']);
    }
}
