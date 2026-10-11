<?php

declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;

class AmountInWordsService
{
    private const ONES = [
        'Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
        'Seventeen', 'Eighteen', 'Nineteen',
    ];

    private const TENS = [
        2 => 'Twenty',
        3 => 'Thirty',
        4 => 'Forty',
        5 => 'Fifty',
        6 => 'Sixty',
        7 => 'Seventy',
        8 => 'Eighty',
        9 => 'Ninety',
    ];

    public function toEnglish(
        float $amount,
        string $currencyWord = 'Taka',
        string $minorCurrencyWord = 'Paisa',
    ): string
    {
        $currencyWord = trim($currencyWord);
        $minorCurrencyWord = trim($minorCurrencyWord);

        if ($currencyWord === '' || $minorCurrencyWord === '') {
            throw new InvalidArgumentException('Currency words cannot be empty.');
        }

        [$major, $minor] = $this->splitAmount($amount);
        $parts = [];

        if ($major > 0 || $minor === 0) {
            $parts[] = $this->englishInteger($major).' '.$currencyWord;
        }

        if ($minor > 0) {
            $parts[] = $this->englishInteger($minor).' '.$minorCurrencyWord;
        }

        return implode(' and ', $parts).' Only';
    }

    private function splitAmount(float $amount): array
    {
        if (! is_finite($amount) || $amount < 0 || $amount > 9999999999999.99) {
            throw new InvalidArgumentException('Amount must be finite and between 0 and 9999999999999.99.');
        }

        $minorUnits = (int) round($amount * 100, 0, PHP_ROUND_HALF_UP);

        return [intdiv($minorUnits, 100), $minorUnits % 100];
    }

    private function englishInteger(int $number): string
    {
        foreach ([10000000 => 'Crore', 100000 => 'Lakh', 1000 => 'Thousand', 100 => 'Hundred'] as $scale => $label) {
            if ($number >= $scale) {
                $remainder = $number % $scale;

                return $this->englishInteger(intdiv($number, $scale)).' '.$label
                    .($remainder > 0 ? ' '.$this->englishInteger($remainder) : '');
            }
        }

        if ($number < 20) {
            return self::ONES[$number];
        }

        return self::TENS[intdiv($number, 10)]
            .($number % 10 > 0 ? ' '.self::ONES[$number % 10] : '');
    }
}
