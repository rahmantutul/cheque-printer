@extends('layouts.user')

@section('title', 'Cheque Details — Cheque Printing System')

@section('page')
    <x-page-header title="Cheque Details" subtitle="Review payment information before printing" />
    <x-card>
        <dl class="grid gap-5 sm:grid-cols-2">
            <div><dt class="text-sm text-text-muted">Payee</dt><dd class="mt-1 font-medium text-text">{{ $cheque->payee_name }}</dd></div>
            <div>
                <dt class="text-sm text-text-muted">Amount</dt>
                <dd class="mt-1 font-medium text-text">{{ $cheque->formatted_amount }}</dd>
                <p class="mt-1 text-xs text-text-muted">Currency: {{ config('cheque.currency_code') }}</p>
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
@endsection
