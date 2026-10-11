@extends('layouts.error')

@section('title', 'Access Denied — Cheque Printing System')
@section('code', '403')
@section('heading', 'Access Denied')
@section('description', 'You do not have permission to view this page.')

@section('action')
    <x-btn-primary :href="url('/dashboard')">Back to dashboard</x-btn-primary>
@endsection
