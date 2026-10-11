@section('title', 'Log in — Cheque Printing System')

<x-guest-layout>
    <x-page-header title="Welcome back" subtitle="Log in to your cheque workspace." />
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        <x-input name="email" label="Email" type="email" :required="true" autofocus autocomplete="username" />
        <x-input name="password" label="Password" type="password" :required="true" autocomplete="current-password" />
        <x-checkbox name="remember" label="Remember me" />
        <div class="flex flex-wrap items-center justify-between gap-4">
            <a class="rounded text-sm text-text-muted underline underline-offset-4 hover:text-text" href="{{ route('password.request') }}">Forgot your password?</a>
            <x-btn-primary type="submit">Log in</x-btn-primary>
        </div>
    </form>
</x-guest-layout>
