<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ChequeTemplate;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class ChequeTemplateService
{
    public function create(array $data, UploadedFile $background, int $userId): ChequeTemplate
    {
        return DB::transaction(function () use ($data, $background, $userId): ChequeTemplate {
            $data['enabled_fields'] = $this->normalizeEnabledFields($data['enabled_fields'] ?? []);
            $data['font_size_pt'] = (int) ($data['font_size_pt'] ?? 12);
            $data['field_positions'] = $this->validateFieldPositions($data['field_positions'], $data['enabled_fields']);
            $data['background_image_path'] = $background->store(config('cheque.upload_paths.cheque_backgrounds'), 'public');
            $data['created_by'] = $userId;

            return ChequeTemplate::create($data);
        });
    }

    public function update(ChequeTemplate $template, array $data, ?UploadedFile $background): ChequeTemplate
    {
        return DB::transaction(function () use ($template, $data, $background): ChequeTemplate {
            $data['enabled_fields'] = $this->normalizeEnabledFields($data['enabled_fields'] ?? []);
            $data['font_size_pt'] = (int) ($data['font_size_pt'] ?? 12);
            $data['field_positions'] = $this->validateFieldPositions($data['field_positions'], $data['enabled_fields']);
            if ($background) {
                if ($template->background_image_path) {
                    Storage::disk('public')->delete($template->background_image_path);
                }
                $data['background_image_path'] = $background->store(config('cheque.upload_paths.cheque_backgrounds'), 'public');
            }
            $template->update($data);

            return $template->refresh();
        });
    }

    public function delete(ChequeTemplate $template, bool $force = false): void
    {
        DB::transaction(function () use ($template, $force): void {
            $count = $template->cheques()->count();
            if ($count && ! $force) {
                throw new DomainException("This template is used by {$count} cheque(s) and cannot be deleted.");
            }
            if ($template->background_image_path) {
                Storage::disk('public')->delete($template->background_image_path);
            }
            $template->delete();
        });
    }

    public function normalizeEnabledFields(array $fields): array
    {
        $allowed = array_keys((new ChequeTemplate)->defaultFieldPositions());
        return array_values(array_intersect($allowed, array_unique($fields)));
    }

    public function validateFieldPositions(array $positions, array $enabledFields): array
    {
        $allowed = array_keys((new ChequeTemplate)->defaultFieldPositions());
        foreach ($enabledFields as $key) {
            if (! in_array($key, $allowed, true)) {
                throw new InvalidArgumentException('Invalid cheque field.');
            }
            if (! isset($positions[$key])) {
                throw new InvalidArgumentException('Enabled cheque fields must have positions.');
            }
            foreach (['x', 'y', 'width', 'height'] as $value) {
                if (! isset($positions[$key][$value]) || ! is_numeric($positions[$key][$value]) || $positions[$key][$value] < 0 || $positions[$key][$value] > 100) {
                    throw new InvalidArgumentException('Field positions must be numeric percentages between 0 and 100.');
                }
            }
        }

        return array_intersect_key($positions, array_flip($enabledFields));
    }
}
