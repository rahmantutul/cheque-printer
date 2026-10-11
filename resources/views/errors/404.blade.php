@extends('layouts.error')

@section('title', 'Page not found — Cheque Printing System')
@section('code', '404')
@section('heading', 'Page not found')
@section('description', 'The page you are looking for does not exist or has moved.')

@section('action')
    <x-btn-primary :href="url('/dashboard')">Back to dashboard</x-btn-primary>
@endsection
