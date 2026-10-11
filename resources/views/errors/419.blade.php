@extends('layouts.error')

@section('title', 'Page expired — Cheque Printing System')
@section('code', '419')
@section('heading', 'Page expired')
@section('description', 'Your session has expired. Refresh the page and try again.')

@section('action')
    <x-btn-primary x-data @click="window.location.reload()">Refresh</x-btn-primary>
@endsection
