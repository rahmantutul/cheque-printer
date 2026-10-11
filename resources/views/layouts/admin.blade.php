@extends('layouts.app')

@section('sidebar-nav')
    @include('partials.sidebar-admin')
@endsection

@section('context-badge')
    <x-role-badge :admin="true" />
@endsection

@section('content')
    @yield('page')
@endsection
