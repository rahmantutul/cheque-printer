@extends('layouts.user')
@section('title', 'Edit Template — Cheque Printing System')
@section('page')
<x-page-header title="Edit Template" subtitle="Update your cheque layout" />
<x-card>
<form method="POST" action="{{ route('user.templates.update',$template) }}" enctype="multipart/form-data" class="space-y-6">
@csrf @method('PUT')
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2"><x-select name="bank_id" label="Bank" :options="$banks->pluck('name','id')->all()" :selected="$template->bank_id" :required="true" /><x-input name="name" label="Name" :value="$template->name" :required="true" /></div>
<div data-upload-zone class="rounded-lg border-2 border-dashed border-border p-6 text-center"><input data-background-input name="background_image" type="file" accept="image/png,image/jpeg" class="sr-only"><button type="button" data-upload-trigger class="font-medium text-primary">↑ Click to upload, or drag an image here</button><p class="mt-1 text-xs text-text-muted">PNG, JPG, JPEG · max 4 MB</p><div data-upload-preview class="mt-3 hidden items-center justify-center gap-3"></div></div>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2"><x-input name="width_mm" label="Width (mm)" :value="$template->width_mm" :required="true" /><x-input name="height_mm" label="Height (mm)" :value="$template->height_mm" :required="true" /></div>
<fieldset class="rounded-lg border border-border p-4"><legend class="font-semibold">Which fields does this cheque have?</legend><div class="mt-3 grid gap-3 sm:grid-cols-2">@foreach(['payee'=>'Pay to','amount_digits'=>'Amount','amount_words_1'=>'Amount in words - line 1','amount_words_2'=>'Amount in words - line 2','date'=>'Date','memo'=>'Memo / Reference'] as $key=>$label)<label class="flex items-center gap-2"><input type="checkbox" name="enabled_fields[]" class="field-toggle" data-field="{{ $key }}" value="{{ $key }}" {{ in_array($key, $enabledFields, true) ? 'checked' : '' }}>{{ $label }}</label>@endforeach</div><p class="mt-3 text-sm text-text-muted">Check only the fields that appear on your physical cheque. Upload your cheque image first to see what you are working with.</p></fieldset>
@include('user.templates.partials.alignment-tool')
<x-input name="font_size_pt" label="Font size (pt)" type="number" min="8" max="16" :value="$fontSizePt" :required="true" />
<div class="flex gap-3"><x-btn-primary type="submit">Save Template</x-btn-primary><x-btn-secondary href="{{ route('user.templates.show',$template) }}">Cancel</x-btn-secondary></div>
</form>
</x-card>
@endsection
