@extends('layouts.admin')
@section('title','Add Bank — Admin')
@section('page')
<x-page-header title="Add Bank" subtitle="Add a bank to the shared directory" />
<x-card><form method="POST" action="{{ route('admin.banks.store') }}" enctype="multipart/form-data" class="max-w-xl space-y-6">@csrf<x-input name="name" label="Name" :required="true" /><x-input name="country" label="Country" value="Bangladesh" :required="true" /><x-input name="logo" label="Logo" type="file" accept=".png,.jpg,.jpeg,.svg" /><x-checkbox name="is_default" label="Set as default bank" /><div class="flex gap-3"><x-btn-primary type="submit">Save</x-btn-primary><x-btn-secondary href="{{ route('admin.banks.index') }}">Cancel</x-btn-secondary></div></form></x-card>
@endsection
