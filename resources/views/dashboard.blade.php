@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="sw-page-header">
        <h1 class="sw-page-title">Dashboard</h1>
        <p class="sw-page-subtitle">Bienvenido, {{ auth()->user()->nombre }}.</p>
    </div>
@endsection
