<div class="space-y-2">
    <x-sidebar-link route="user.dashboard" icon="home" label="Dashboard" :active="request()->routeIs('user.dashboard')" />
    <x-sidebar-link route="user.banks.index" icon="building-library" label="My Banks" :active="request()->routeIs('user.banks.*')" />
    <x-sidebar-link route="user.templates.index" icon="document-duplicate" label="Templates" :active="request()->routeIs('user.templates.*')" />
    <x-sidebar-link route="user.cheques.index" icon="document-text" label="Cheques" :active="request()->routeIs('user.cheques.*')" />
    <x-sidebar-link route="user.settings.index" icon="cog-6-tooth" label="Settings" :active="request()->routeIs('user.settings.*')" />
</div>
