@extends('layouts.error')

@section('title', 'Something went wrong — Cheque Printing System')
@section('code', '500')
@section('heading', 'Something went wrong')
@section('description', 'We could not complete your request. Please try again.')

@section('action')
    <x-btn-primary :href="url('/')">Try again</x-btn-primary>
@endsection
