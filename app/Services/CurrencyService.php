<?php

declare(strict_types=1);

namespace App\Services;

class CurrencyService
{
    public function all(): array { try { return (array) config('cheque.currencies', []); } catch (\Throwable) { return require dirname(__DIR__, 2).'/config/currencies.php'; } }
    public function find(string $code): ?array { return $this->all()[strtoupper($code)] ?? null; }
    public function default(): array { try { $code = (string) config('cheque.default_currency', 'BDT'); } catch (\Throwable) { $code = 'BDT'; } return $this->find($code) ?? []; }
    public function current(): array { return $this->default(); }
    public function symbolPosition(string $code): string { return $this->find($code)['position'] ?? 'before'; }
}
