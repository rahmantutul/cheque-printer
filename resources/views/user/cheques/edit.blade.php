@extends('layouts.user')

@section('title', 'Edit Cheque — Cheque Printing System')

@section('page')
    @php
        $templateOptions = $templates->map(fn ($template) => [
            'id' => $template->id,
            'bank_id' => $template->bank_id,
            'name' => $template->name,
            'has_memo' => array_key_exists('memo', $template->field_positions ?? []),
        ])->values();
    @endphp

    <x-page-header title="Edit Cheque" subtitle="Update your draft cheque" />

    <form
        method="POST"
        action="{{ route('user.cheques.update', $cheque) }}"
        x-data="chequeForm({
            templates: @js($templateOptions),
            initialBankId: @js((string) old('bank_id', $cheque->bank_id)),
            initialTemplateId: @js((string) old('template_id', $cheque->template_id)),
            initialAmount: @js((string) old('amount', $cheque->amount)),
            amountUrl: @js(route('user.cheques.amount-in-words'))
        })"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        <x-card>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-select name="bank_id" label="Bank" :options="$banks->pluck('name', 'id')->all()"
                    x-model="bankId" @change="onBankChange()" :required="true" />

                <div>
                    <label for="template_id" class="mb-2 block text-sm font-medium text-text">
                        Template <span aria-hidden="true" class="text-danger">*</span>
                    </label>
                    <select id="template_id" name="template_id" x-model="templateId" @change="onTemplateChange()"
                        class="form-control w-full" required>
                        <option value="">Choose a template</option>
                        <template x-for="template in filteredTemplates" :key="template.id">
                            <option :value="template.id" x-text="template.name"></option>
                        </template>
                    </select>
                    <x-field-feedback name="template_id" id="template_id" />
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <x-input name="payee_name" label="Payee name"
                    :value="old('payee_name', $cheque->payee_name)" :required="true" />
                <x-input name="cheque_date" label="Cheque date" type="date"
                    :value="old('cheque_date', $cheque->cheque_date?->toDateString())" :required="true" />
            </div>

            <div class="mt-4">
                <x-input name="amount" label="Amount" type="number" step="0.01" min="0.01"
                    x-model="amount" @input.debounce.400ms="fetchWords()" :required="true" />
                <p x-show="pending" x-cloak class="mt-2 text-sm text-text-muted">Calculating...</p>
                <p x-show="error" x-cloak class="mt-2 text-sm text-danger" x-text="error"></p>
                <div x-show="words.full" x-cloak class="mt-2 rounded-md bg-surface-alt p-3 text-sm">
                    <p><strong>Amount in words:</strong> <span x-text="words.full"></span></p>
                    <template x-if="words.line_1 && words.line_2">
                        <p class="mt-1 text-xs text-text-muted">
                            Line 1: <span x-text="words.line_1"></span><br>
                            Line 2: <span x-text="words.line_2"></span>
                        </p>
                    </template>
                </div>
            </div>

            <div x-show="hasMemo" x-cloak class="mt-4">
                <x-textarea name="memo" label="Memo / Reference"
                    :value="old('memo', $cheque->memo)" rows="2" />
            </div>
        </x-card>

        <div class="flex flex-wrap gap-3">
            <x-btn-primary type="submit">Update Cheque</x-btn-primary>
            <x-btn-secondary href="{{ route('user.cheques.show', $cheque) }}">Cancel</x-btn-secondary>
        </div>
    </form>
@endsection
