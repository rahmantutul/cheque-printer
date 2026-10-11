@extends('layouts.user')

@section('title', 'Dashboard — Cheque Printing System')

@section('page')
    <x-page-header title="Dashboard" subtitle="Your cheques and templates at a glance" />
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4" aria-label="Dashboard statistics">
        <x-stat-card label="My Cheques" icon="document-text" context="user" />
        <x-stat-card label="Drafts" icon="clock" context="user" />
        <x-stat-card label="Printed" icon="printer" context="user" />
        <x-stat-card label="Templates" icon="document-duplicate" context="user" />
    </div>
    {{-- TODO (Phase 3): Populate these cards using the signed-in user's statistics. --}}
@endsection
