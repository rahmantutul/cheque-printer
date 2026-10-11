@extends('layouts.user')

@section('title', 'Cheque Details — Cheque Printing System')

@section('page')
    @php($currency = app(\App\Services\CurrencyService::class)->current())
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
    </x-card>
    @if ($cheque->status === 'draft')
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('user.cheques.edit', $cheque) }}" class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white">Edit</a>
            <form method="POST" action="{{ route('user.cheques.destroy', $cheque) }}" id="delete-cheque-{{ $cheque->id }}">
                @csrf
                @method('DELETE')
                <button type="button" class="inline-flex items-center rounded-md bg-danger px-4 py-2 text-sm font-medium text-white" @click="$dispatch('open-confirm', { action: 'delete-cheque-{{ $cheque->id }}' })">Delete</button>
            </form>
        </div>
    @endif
@endsection
