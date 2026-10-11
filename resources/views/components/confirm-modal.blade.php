@props(['title' => 'Confirm this action', 'description' => 'Are you sure you want to continue? This action may not be reversible.'])

<div x-data="confirmModal" @open-confirm.window="open($event.detail)">
    <dialog x-ref="dialog" @close="restoreFocus()" aria-labelledby="confirm-title" aria-describedby="confirm-description"
        class="w-full max-w-md rounded-lg border border-border bg-surface p-0 text-text shadow-md backdrop:bg-text/50">
        <div class="p-6">
            <div class="mb-4 inline-flex rounded-lg bg-red-50 p-3 text-danger"><x-icon name="shield-exclamation" class="h-6 w-6" /></div>
            <h2 id="confirm-title" class="text-lg font-semibold">{{ $title }}</h2>
            <p id="confirm-description" class="mt-2 text-sm leading-6 text-text-muted">{{ $description }}</p>
            <p x-text="error" :class="{ 'hidden': !error }" class="mt-4 text-sm text-danger" role="alert"></p>
        </div>
        <div class="flex flex-wrap justify-end gap-3 border-t border-border bg-surface-alt px-6 py-4">
            <x-btn-secondary x-ref="cancel" @click="close()">Cancel</x-btn-secondary>
            <x-btn-danger @click="confirm()">Confirm</x-btn-danger>
        </div>
    </dialog>
</div>
