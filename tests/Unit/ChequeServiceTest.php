<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\AmountInWordsService;
use App\Services\ChequeService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ChequeServiceTest extends TestCase
{
    #[DataProvider('longAmounts')]
    public function test_two_line_split_is_complete_and_bounded(string $fullText): void
    {
        $lines = $this->service()->splitAmountInWords($fullText, true);

        $this->assertNotNull($lines['line_1']);
        $this->assertNotNull($lines['line_2']);
        $this->assertSame($fullText, $lines['line_1'].' '.$lines['line_2']);

        $total = mb_strlen($fullText);
        $this->assertGreaterThanOrEqual(0.30, mb_strlen($lines['line_1']) / $total);
        $this->assertLessThanOrEqual(0.70, mb_strlen($lines['line_1']) / $total);
        // The removed boundary space belongs to the second segment for ratio accounting.
        $this->assertGreaterThanOrEqual(0.30, (mb_strlen($lines['line_2']) + 1) / $total);
        $this->assertLessThanOrEqual(0.70, (mb_strlen($lines['line_2']) + 1) / $total);
    }

    public static function longAmounts(): array
    {
        return [
            ['Forty Five Lakh Fifty Six Thousand Two Hundred Twenty Two Taka Only'],
            ['One Crore Twenty Three Lakh Forty Five Thousand Six Hundred Seventy Eight Taka and Ninety Paisa Only'],
        ];
    }

    public function test_preferred_scale_boundaries_match_cheque_phrases(): void
    {
        $service = $this->service();

        $this->assertSame([
            'line_1' => 'Forty Five Lakh Fifty Six Thousand Two Hundred',
            'line_2' => 'Twenty Two Taka Only',
        ], $service->splitAmountInWords('Forty Five Lakh Fifty Six Thousand Two Hundred Twenty Two Taka Only', true));

        $this->assertSame([
            'line_1' => 'One Crore Twenty Three Lakh Forty Five Thousand',
            'line_2' => 'Six Hundred Seventy Eight Taka and Ninety Paisa Only',
        ], $service->splitAmountInWords('One Crore Twenty Three Lakh Forty Five Thousand Six Hundred Seventy Eight Taka and Ninety Paisa Only', true));
    }

    public function test_short_amount_stays_on_one_line(): void
    {
        $this->assertSame(
            ['line_1' => null, 'line_2' => null],
            $this->service()->splitAmountInWords('One Thousand Two Hundred Fifty Taka Only', true),
        );
    }

    private function service(): ChequeService
    {
        return new ChequeService(new AmountInWordsService);
    }
}
