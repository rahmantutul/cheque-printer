<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Bank;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BankService
{
    public function create(array $data, ?UploadedFile $logo = null): Bank
    {
        return DB::transaction(function () use ($data, $logo): Bank {
            if ($logo) {
                $data['logo_path'] = $this->storeLogo($logo);
            }
            $bank = Bank::create($data);
            if ($bank->is_default) {
                $bank->markAsDefault();
            }

            return $bank;
        });
    }

    public function update(Bank $bank, array $data, ?UploadedFile $logo = null): Bank
    {
        return DB::transaction(function () use ($bank, $data, $logo): Bank {
            if ($logo) {
                $old = $bank->logo_path;
                $data['logo_path'] = $this->storeLogo($logo);
                if ($old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $bank->update($data);
            if ($bank->is_default) {
                $bank->markAsDefault();
            }

            return $bank->refresh();
        });
    }

    public function delete(Bank $bank, bool $force = false): void
    {
        DB::transaction(function () use ($bank, $force): void {
            if (! $force && $bank->cheques()->exists()) {
                throw new DomainException('This bank cannot be deleted because cheques reference it.');
            }
            if ($bank->logo_path) {
                Storage::disk('public')->delete($bank->logo_path);
            }
            $bank->delete();
        });
    }

    public function toggleDefault(Bank $bank): void
    {
        $bank->markAsDefault();
    }

    private function storeLogo(UploadedFile $logo): string
    {
        $path = trim(config('cheque.upload_paths.bank_logos'), '/').'/'.$logo->hashName();
        Storage::disk('public')->put($path, $logo->get());

        return $path;
    }
}
