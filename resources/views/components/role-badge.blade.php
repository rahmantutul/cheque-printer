@props(['admin' => false])

<span @class(['rounded px-2 py-0.5 text-xs font-semibold text-white', 'bg-danger' => $admin, 'bg-user' => ! $admin])>
    {{ $admin ? 'ADMIN' : 'USER' }}
</span>
