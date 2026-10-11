<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="font-sans text-text bg-surface-alt antialiased">
        <div x-data="appShell" @resize.window.debounce="resize()" @keydown.escape.window="if (sidebarOpen) closeSidebar(); menuOpen = false">
            <a href="#main" class="sr-only z-50 rounded bg-surface p-4 font-semibold focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Skip to content</a>

            <div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" class="fixed inset-0 z-30 bg-text/50 lg:hidden" @click="closeSidebar()" aria-hidden="true"></div>

            <aside id="sidebar" x-ref="sidebar" x-cloak :class="sidebarOpen ? 'flex' : 'hidden lg:flex'"
                :role="sidebarOpen && !desktop ? 'dialog' : null"
                :aria-modal="sidebarOpen && !desktop ? 'true' : null"
                aria-label="Main navigation" @keydown="trapSidebar($event)"
                class="fixed inset-y-0 left-0 z-40 w-64 flex-col text-white {{ auth()->user()->isAdmin() ? 'bg-admin' : 'bg-user' }}">
                <div class="flex h-20 shrink-0 items-center justify-between gap-4 border-b border-white/10 px-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded font-semibold tracking-tight focus-visible:ring-white">
                        <span class="flex h-8 w-8 items-center justify-center rounded bg-white/10"><x-icon name="document-text" /></span>
                        <span>Cheque Studio</span>
                    </a>
                    <button type="button" x-ref="sidebarClose" @click="closeSidebar()" class="rounded p-2 hover:bg-white/10 focus-visible:ring-white lg:hidden" aria-label="Close navigation">
                        <x-icon name="x-mark" />
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto px-4 py-6">
                    <p class="mb-4 px-3 text-xs font-semibold uppercase tracking-widest text-white/70">{{ auth()->user()->isAdmin() ? 'Administration' : 'Workspace' }}</p>
                    <nav aria-label="{{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}">
                        @hasSection('sidebar-nav')
                            @yield('sidebar-nav')
                        @else
                            @include(auth()->user()->isAdmin() ? 'partials.sidebar-admin' : 'partials.sidebar-user')
                        @endif
                    </nav>
                </div>
                <div class="border-t border-white/10 px-6 py-6">
                    <p class="text-sm font-medium">Cheque Printing System</p>
                    <p class="mt-1 text-xs text-white/70">Your banking workspace</p>
                </div>
            </aside>

            <div class="min-w-0 lg:pl-64" :inert="sidebarOpen && !desktop">
                <header class="flex min-h-20 items-center justify-between gap-4 border-b border-border bg-surface px-4 py-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button" x-ref="sidebarToggle" @click="sidebarOpen = true" :aria-expanded="sidebarOpen.toString()" aria-controls="sidebar" aria-label="Open navigation" class="shrink-0 rounded p-2 text-text-muted hover:bg-surface-alt lg:hidden">
                            <x-icon name="bars-3" />
                        </button>
                        <span class="hidden text-sm font-semibold sm:inline">{{ config('app.name', 'Cheque Printing System') }}</span>
                        <span class="text-sm font-semibold sm:hidden">Cheques</span>
                        @hasSection('context-badge')
                            @yield('context-badge')
                        @else
                            <x-role-badge :admin="auth()->user()->isAdmin()" />
                        @endif
                    </div>
                    <div class="relative shrink-0" @click.outside="menuOpen = false" @focusout="if (!$el.contains($event.relatedTarget)) menuOpen = false">
                        <button type="button" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen.toString()" aria-controls="account-menu" class="flex items-center gap-2 rounded p-2 text-sm hover:bg-surface-alt" aria-label="Account menu">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-surface-alt text-xs font-semibold">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                            <span class="hidden max-w-48 truncate sm:block">{{ auth()->user()->name }}</span>
                            <x-icon name="chevron-down" class="h-4 w-4" />
                        </button>
                        <div id="account-menu" x-cloak :class="menuOpen ? 'block' : 'hidden'" class="absolute right-0 z-20 mt-2 w-56 rounded-lg border border-border bg-surface p-2 shadow-md">
                            <p class="truncate px-3 py-2 text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <a href="{{ route('profile.edit') }}" class="block rounded px-3 py-2 text-sm hover:bg-surface-alt">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded px-3 py-2 text-left text-sm text-danger hover:bg-red-50">Log out</button>
                            </form>
                        </div>
                    </div>
                </header>
                <main id="main" tabindex="-1" class="min-h-screen min-w-0 p-6">
                    <div class="mx-auto max-w-7xl">
                        <x-flash />
                        @isset($header)
                            <div class="mb-6">{{ $header }}</div>
                        @endisset
                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </div>
                </main>
            </div>
            <x-confirm-modal />
        </div>
        @stack('scripts')
    </body>
</html>
