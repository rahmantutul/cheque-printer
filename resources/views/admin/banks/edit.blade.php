@extends('layouts.admin')
@section('title','Edit Bank — Admin')
@section('page')
<x-page-header title="Edit Bank" subtitle="Update bank details" />
<x-card><form method="POST" action="{{ route('admin.banks.update',$bank) }}" enctype="multipart/form-data" class="max-w-xl space-y-6">@csrf @method('PUT')<x-input name="name" label="Name" :value="$bank->name" :required="true" /><x-input name="country" label="Country" :value="$bank->country" :required="true" /><x-input name="logo" label="Replace logo" type="file" accept=".png,.jpg,.jpeg,.svg" />@if($bank->logo_url)<img src="{{ $bank->logo_url }}" alt="{{ $bank->name }} logo" class="h-16 w-16 object-contain">@endif<x-checkbox name="is_default" label="Set as default bank" :value="$bank->is_default" /><div class="flex gap-3"><x-btn-primary type="submit">Save</x-btn-primary><x-btn-secondary href="{{ route('admin.banks.show',$bank) }}">Cancel</x-btn-secondary></div></form></x-card>
@endsection
