<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_id',
        'template_id',
        'payee_name',
        'amount',
        'amount_in_words',
        'amount_words_line_1',
        'amount_words_line_2',
        'cheque_date',
        'memo',
        'status',
        'printed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cheque_date' => 'date',
        'printed_at' => 'datetime',
    ];

    public function getFormattedAmountAttribute(): string
    {
        return number_format((float) $this->amount, 2, '.', ',');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ChequeTemplate::class, 'template_id');
    }
}
