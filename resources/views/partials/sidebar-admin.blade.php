<div class="space-y-2">
    <x-sidebar-link route="admin.dashboard" icon="home" label="Dashboard" :active="request()->routeIs('admin.dashboard')" />
    <x-sidebar-link route="admin.users.index" icon="users" label="Users" :active="request()->routeIs('admin.users.*')" />
    <x-sidebar-link route="admin.banks.index" icon="building-library" label="Banks" :active="request()->routeIs('admin.banks.*')" />
    <x-sidebar-link route="admin.templates.index" icon="document-duplicate" label="Cheque Templates" :active="request()->routeIs('admin.templates.*')" />
    <x-sidebar-link route="admin.activity.index" icon="clipboard-document-list" label="Activity Log" :active="request()->routeIs('admin.activity.*')" />
</div>
