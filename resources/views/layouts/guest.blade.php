<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-surface-alt font-sans text-text antialiased">
        <main id="main" class="flex min-h-screen flex-col items-center justify-center px-6 py-12">
            <a href="{{ url('/') }}" class="mb-8 flex items-center gap-3 rounded text-lg font-semibold">
                <span class="rounded-lg bg-primary p-3 text-white"><x-icon name="document-text" class="h-6 w-6" /></span>
                <span>Cheque Printing System</span>
            </a>
            <div class="w-full max-w-md">
                <x-flash />
                <x-card>{{ $slot }}</x-card>
            </div>
        </main>
        @stack('scripts')
    </body>
</html>
