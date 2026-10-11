<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-surface-alt font-sans text-text antialiased">
        <main class="flex min-h-screen items-center justify-center px-6 py-12">
            <div class="w-full max-w-lg">
                <x-card>
                    <div class="text-center">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-red-50 text-danger">
                            <x-icon name="shield-exclamation" class="h-8 w-8" />
                        </div>
                        <p class="mb-2 text-sm font-semibold tracking-widest text-text-muted">@yield('code')</p>
                        <h1 class="text-2xl font-semibold tracking-tight">@yield('heading')</h1>
                        <p class="mt-3 text-sm leading-6 text-text-muted">@yield('description')</p>
                        @hasSection('action')
                            <div class="mt-6">@yield('action')</div>
                        @endif
                    </div>
                </x-card>
            </div>
        </main>
    </body>
</html>
