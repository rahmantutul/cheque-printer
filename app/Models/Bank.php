<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'logo_path',
        'is_default',
        'created_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function chequeTemplates(): HasMany
    {
        return $this->hasMany(ChequeTemplate::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn (Builder $builder) => $builder->where(function (Builder $nested) use ($term): void {
            $nested->where('name', 'like', '%'.$term.'%')->orWhere('country', 'like', '%'.$term.'%');
        }));
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function markAsDefault(): void
    {
        DB::transaction(function (): void {
            static::query()->whereKeyNot($this->getKey())->update(['is_default' => false]);
            $this->update(['is_default' => true]);
        });
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null;
    }

    public function templates(): HasMany
    {
        return $this->hasMany(ChequeTemplate::class);
    }

    public function cheques(): HasMany
    {
        return $this->hasMany(Cheque::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
