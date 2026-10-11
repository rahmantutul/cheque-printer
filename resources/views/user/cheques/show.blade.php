@extends('layouts.user')

@section('title', 'Cheque Details — Cheque Printing System')

@section('page')
    @php($currency = app(\App\Services\CurrencyService::class)->current())
    <a href="{{ route('user.cheques.index') }}" class="text-sm text-text-muted hover:text-text">
        ← Back to Cheques
    </a>
    <x-page-header title="Cheque Details" subtitle="Review payment information before printing" />
    <x-card>
        <dl class="grid gap-5 sm:grid-cols-2">
            <div><dt class="text-sm text-text-muted">Payee</dt><dd class="mt-1 font-medium text-text">{{ $cheque->payee_name }}</dd></div>
            <div>
                <dt class="text-sm text-text-muted">Amount</dt>
                <dd class="mt-1 font-medium text-text">{{ $currency['position'] === 'before' ? $currency['symbol'].' ' : '' }}{{ $cheque->formatted_amount }}{{ $currency['position'] === 'after' ? ' '.$currency['symbol'] : '' }}</dd>
                <p class="mt-1 text-xs text-text-muted">Currency: {{ $currency['name'] }}</p>
            </div>
            <div><dt class="text-sm text-text-muted">Cheque date</dt><dd class="mt-1 font-medium text-text">{{ $cheque->cheque_date->format('d M Y') }}</dd></div>
            <div><dt class="text-sm text-text-muted">Status</dt><dd class="mt-1 font-medium text-text">{{ ucfirst($cheque->status) }}</dd></div>
            <div class="sm:col-span-2">
                <dt class="text-sm text-text-muted">Amount in words</dt>
                <dd class="mt-1 font-medium text-text">{{ $cheque->amount_in_words }}</dd>
                @if ($cheque->amount_words_line_1 && $cheque->amount_words_line_2)
                    <p class="mt-2 text-sm text-text-muted">Line 1: {{ $cheque->amount_words_line_1 }}<br>Line 2: {{ $cheque->amount_words_line_2 }}</p>
                @endif
            </div>
            @if ($cheque->memo)
                <div class="sm:col-span-2"><dt class="text-sm text-text-muted">Memo / Reference</dt><dd class="mt-1 font-medium text-text">{{ $cheque->memo }}</dd></div>
            @endif
        </dl>
        <dl class="mt-6 grid gap-5 border-t border-border pt-5 sm:grid-cols-2">
            <div><dt class="text-sm text-text-muted">Bank</dt><dd class="mt-1 font-medium text-text">{{ $cheque->bank->name }}</dd></div>
            <div><dt class="text-sm text-text-muted">Template</dt><dd class="mt-1 font-medium text-text">{{ $cheque->template?->name ?? '—' }}</dd></div>
            <div><dt class="text-sm text-text-muted">Created</dt><dd class="mt-1 font-medium text-text">{{ $cheque->created_at->format('d M Y H:i') }}</dd></div>
            <div><dt class="text-sm text-text-muted">Printed at</dt><dd class="mt-1 font-medium text-text">{{ $cheque->printed_at?->format('d M Y H:i') ?? '—' }}</dd></div>
        </dl>
    </x-card>
    @if ($cheque->status === 'draft')
        <div class="mt-6 flex flex-wrap gap-3">
            <x-btn-secondary href="{{ route('user.cheques.edit', $cheque) }}">Edit</x-btn-secondary>
            <form method="POST" action="{{ route('user.cheques.mark-printed', $cheque) }}" class="inline">
                @csrf
                <x-btn-primary type="submit">Mark as Printed</x-btn-primary>
            </form>
            <form method="POST" action="{{ route('user.cheques.destroy', $cheque) }}" id="delete-cheque-{{ $cheque->id }}">
                @csrf
                @method('DELETE')
                <x-btn-danger type="button" @click="$dispatch('open-confirm', { action: 'delete-cheque-{{ $cheque->id }}' })">Delete</x-btn-danger>
            </form>
        </div>
    @endif
@endsection
