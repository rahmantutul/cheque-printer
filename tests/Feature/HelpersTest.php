<?php

declare(strict_types=1);

namespace Tests\Feature;

use InvalidArgumentException;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_helpers_resolve_the_english_service_and_format_money(): void
    {
        $this->assertSame('One Taka Only', amount_in_words(1));
        $this->assertSame('One Dollar Only', amount_in_words(1, 'Dollar', 'Cent'));
        $this->assertSame('BDT 1,250.50', format_money(1250.50));
        $this->assertSame('USD 1,250.50', format_money(1250.50, 'usd'));
    }

    public function test_empty_amount_currency_word_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        amount_in_words(1, '');
    }

    public function test_invalid_currency_codes_are_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        format_money(1, '<script>');
    }
}
