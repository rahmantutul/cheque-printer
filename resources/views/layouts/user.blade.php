@extends('layouts.app')

@section('sidebar-nav')
    @include('partials.sidebar-user')
@endsection

@section('context-badge')
    <x-role-badge :admin="false" />
@endsection

@section('content')
    @yield('page')
@endsection
