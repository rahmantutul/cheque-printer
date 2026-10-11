<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\AmountInWordsService;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AmountInWordsServiceTest extends TestCase
{
    #[DataProvider('amounts')]
    public function test_amounts_use_english_indian_numbering(float $amount, string $expected): void
    {
        $this->assertSame($expected, (new AmountInWordsService)->toEnglish($amount));
    }

    public static function amounts(): array
    {
        return [
            [1250.50, 'One Thousand Two Hundred Fifty Taka and Fifty Paisa Only'],
            [125000.0, 'One Lakh Twenty Five Thousand Taka Only'],
            [1000000.0, 'Ten Lakh Taka Only'],
            [12345678.90, 'One Crore Twenty Three Lakh Forty Five Thousand Six Hundred Seventy Eight Taka and Ninety Paisa Only'],
            [0.99, 'Ninety Nine Paisa Only'],
            [0.0, 'Zero Taka Only'],
            [1.995, 'Two Taka Only'],
        ];
    }

    public function test_currency_words_can_be_customized(): void
    {
        $this->assertSame(
            'One Hundred Dollars and Fifty Cents Only',
            (new AmountInWordsService)->toEnglish(100.50, 'Dollars', 'Cents'),
        );
    }

    #[DataProvider('invalidAmounts')]
    public function test_invalid_amounts_are_rejected(float $amount): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new AmountInWordsService)->toEnglish($amount);
    }

    public static function invalidAmounts(): array
    {
        return [[-1.0], [INF], [NAN], [10000000000000.0]];
    }

    public function test_empty_currency_word_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new AmountInWordsService)->toEnglish(1, '');
    }
}
