<?php

declare(strict_types=1);

use App\Services\AmountInWordsService;

function amount_in_words(
    float $amount,
    string $currencyWord = 'Taka',
    string $minorCurrencyWord = 'Paisa'
): string
{
    return app(AmountInWordsService::class)->toEnglish($amount, $currencyWord, $minorCurrencyWord);
}

function format_money(float $amount, string $currency = 'BDT'): string
{
    if (! is_finite($amount)) {
        throw new InvalidArgumentException('Amount must be finite.');
    }

    $currency = strtoupper(trim($currency));

    if (preg_match('/^[A-Z]{3}$/D', $currency) !== 1) {
        throw new InvalidArgumentException('Currency must be a three-letter code.');
    }

    return $currency.' '.number_format($amount, 2, '.', ',');
}
