@section('title', 'Cheque Printing System')

<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-primary-dark">Your cheque workspace</p>
            <h1 class="text-2xl font-semibold tracking-tight">Cheque Printing System</h1>
            <p class="mt-3 text-sm leading-6 text-text-muted">One workspace for your banks, cheque templates, and business payments.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            @auth
                <x-btn-primary :href="route('dashboard')">Open dashboard <x-icon name="arrow-right" /></x-btn-primary>
            @else
                <x-btn-primary :href="route('login')">Log in <x-icon name="arrow-right" /></x-btn-primary>
                <x-btn-secondary :href="route('register')">Create account</x-btn-secondary>
            @endauth
        </div>
    </div>
</x-guest-layout>
