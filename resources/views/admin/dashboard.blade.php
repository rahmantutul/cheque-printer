@extends('layouts.admin')

@section('title', 'Admin Dashboard — Cheque Printing System')

@section('page')
    <x-page-header title="Admin Dashboard" subtitle="Manage banks, users, and templates" />
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4" aria-label="Dashboard statistics">
        <x-stat-card label="Total Users" icon="users" context="admin" />
        <x-stat-card label="Total Banks" icon="building-library" context="admin" />
        <x-stat-card label="Total Templates" icon="document-duplicate" context="admin" />
        <x-stat-card label="Total Cheques" icon="document-text" context="admin" />
    </div>
    {{-- TODO (Phase 3): Populate these cards using administration-wide statistics. --}}
@endsection
