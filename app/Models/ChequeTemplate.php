<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ChequeTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'name',
        'background_image_path',
        'width_mm',
        'height_mm',
        'font_size_pt',
        'field_positions',
        'enabled_fields',
        'created_by',
    ];

    protected $casts = [
        'field_positions' => 'array',
        'enabled_fields' => 'array',
        'width_mm' => 'decimal:2',
        'height_mm' => 'decimal:2',
        'font_size_pt' => 'integer',
    ];

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('created_by', $userId);
    }

    public function getBackgroundUrlAttribute(): ?string
    {
        return $this->background_image_path ? Storage::disk('public')->url($this->background_image_path) : null;
    }

    public function defaultFieldPositions(): array
    {
        return ['payee' => ['x' => 8.5, 'y' => 22.0, 'width' => 62.0, 'height' => 6.0], 'amount_digits' => ['x' => 72.0, 'y' => 22.0, 'width' => 24.0, 'height' => 6.0], 'amount_words_1' => ['x' => 8.5, 'y' => 32.0, 'width' => 88.0, 'height' => 6.0], 'amount_words_2' => ['x' => 8.5, 'y' => 40.0, 'width' => 88.0, 'height' => 6.0], 'date' => ['x' => 78.0, 'y' => 12.0, 'width' => 18.0, 'height' => 6.0], 'memo' => ['x' => 8.5, 'y' => 52.0, 'width' => 60.0, 'height' => 6.0]];
    }

    public function hasAllFields(): bool
    {
        return count(array_intersect(array_keys($this->field_positions ?? []), array_keys($this->defaultFieldPositions()))) === 5;
    }

    public function effectiveEnabledFields(): array
    {
        return array_values(array_unique($this->enabled_fields ?: ['payee', 'amount_digits', 'amount_words_1', 'amount_words_2', 'date']));
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cheques(): HasMany
    {
        return $this->hasMany(Cheque::class, 'template_id');
    }
}
