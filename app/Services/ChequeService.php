<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cheque;
use App\Models\ChequeTemplate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChequeService
{
    private const PREFERRED_BREAK_WORDS = ['Lakh', 'Crore', 'Thousand', 'Hundred'];

    private const MIN_SPLIT_LENGTH = 50;

    public function __construct(private readonly AmountInWordsService $amounts)
    {
    }

    public function create(array $data, int $userId): Cheque
    {
        return DB::transaction(function () use ($data, $userId): Cheque {
            $template = ChequeTemplate::findOrFail($data['template_id']);
            $full = $this->amounts->toEnglish(
                (float) $data['amount'],
                null,
            );
            $lines = $this->splitAmountInWords($full, $this->usesTwoLines($template));

            return Cheque::create(array_merge($data, [
                'user_id' => $userId,
                'amount_in_words' => $full,
                'amount_words_line_1' => $lines['line_1'],
                'amount_words_line_2' => $lines['line_2'],
                'status' => 'draft',
            ]));
        });
    }

    public function update(Cheque $cheque, array $data): Cheque
    {
        return DB::transaction(function () use ($cheque, $data): Cheque {
            $template = ChequeTemplate::findOrFail($data['template_id']);
            $full = $this->amounts->toEnglish(
                (float) $data['amount'],
                null,
            );
            $lines = $this->splitAmountInWords($full, $this->usesTwoLines($template));

            $cheque->update(array_merge($data, [
                'amount_in_words' => $full,
                'amount_words_line_1' => $lines['line_1'],
                'amount_words_line_2' => $lines['line_2'],
            ]));

            return $cheque->refresh();
        });
    }

    public function delete(Cheque $cheque): void
    {
        $cheque->delete();
    }

    public function markAsPrinted(Cheque $cheque): void
    {
        if ($cheque->status !== 'printed') {
            $cheque->update(['status' => 'printed', 'printed_at' => Carbon::now()]);
        }
    }

    public function splitAmountInWords(string $fullText, bool $twoLineMode): array
    {
        $length = mb_strlen($fullText);

        if (! $twoLineMode || $length < self::MIN_SPLIT_LENGTH) {
            return ['line_1' => null, 'line_2' => null];
        }

        $boundaries = $this->wordBoundaries($fullText, $length);
        $preferred = array_values(array_filter(
            $boundaries,
            fn (array $boundary): bool => in_array($boundary['word'], self::PREFERRED_BREAK_WORDS, true)
                && $boundary['ratio'] >= 0.30
                && $boundary['ratio'] <= 0.70
                && abs($boundary['ratio'] - 0.50) <= 0.25,
        ));

        if ($preferred !== []) {
            $boundary = $length < 80
                ? collect($preferred)->sortByDesc('position')->first()
                : collect($preferred)->sortBy(fn (array $item): float => abs($item['ratio'] - 0.50))->first();
        } else {
            $valid = array_values(array_filter(
                $boundaries,
                fn (array $boundary): bool => $boundary['ratio'] >= 0.30 && $boundary['ratio'] <= 0.70,
            ));
            $boundary = collect($valid)->sortBy(fn (array $item): float => abs($item['ratio'] - 0.50))->first();
        }

        if (! is_array($boundary)) {
            return ['line_1' => null, 'line_2' => null];
        }

        return [
            'line_1' => mb_substr($fullText, 0, $boundary['position']),
            'line_2' => mb_substr($fullText, $boundary['position'] + 1),
        ];
    }

    private function wordBoundaries(string $text, int $length): array
    {
        preg_match_all('/(\S+)(?:\s+|$)/u', $text, $matches, PREG_OFFSET_CAPTURE);
        $boundaries = [];

        foreach ($matches[1] as [$word, $byteOffset]) {
            $position = mb_strlen(substr($text, 0, $byteOffset).$word);

            if ($position < $length) {
                $boundaries[] = [
                    'word' => $word,
                    'position' => $position,
                    'ratio' => $position / $length,
                ];
            }
        }

        return $boundaries;
    }

    private function usesTwoLines(ChequeTemplate $template): bool
    {
        return isset($template->field_positions['amount_words_1'], $template->field_positions['amount_words_2']);
    }
}
