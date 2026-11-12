@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="sw-auth-shell">
        <div class="sw-card sw-auth-card">
            <h1 class="sw-card-title sw-auth-title">{{ config('app.name') }}</h1>
            <p class="sw-hint sw-auth-subtitle">Ingresa tus credenciales para continuar.</p>

            @if ($errors->any() && ! $errors->has('email') && ! $errors->has('password'))
                <div class="sw-alert sw-alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ Route::has('login.authenticate') ? route('login.authenticate') : '#' }}" class="needs-validation" novalidate>
                @csrf

                <div class="sw-field">
                    <label class="sw-label" for="email">Correo electrónico</label>
                    <div class="sw-input-icon-wrap">
                        <i class="fa-solid fa-envelope sw-input-icon"></i>
                        <input type="email" class="sw-input sw-input-has-icon @error('email') sw-error @enderror"
                               name="email" id="email" value="{{ old('email') }}"
                               autocomplete="email" required autofocus>
                    </div>
                    {!! $errors->first('email', '<span class="sw-hint-error">:message</span>') !!}
                </div>

                <div class="sw-field">
                    <label class="sw-label" for="password">Contraseña</label>
                    <div class="sw-input-icon-wrap">
                        <i class="fa-solid fa-lock sw-input-icon"></i>
                        <input type="password" class="sw-input sw-input-has-icon @error('password') sw-error @enderror"
                               name="password" id="password" autocomplete="current-password" required>
                    </div>
                    {!! $errors->first('password', '<span class="sw-hint-error">:message</span>') !!}
                </div>

                <label class="sw-auth-remember">
                    <input type="checkbox" class="sw-checkbox-input" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    Recordarme
                </label>

                <button type="submit" class="sw-btn sw-btn-primary sw-auth-submit">
                    <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
                </button>
            </form>
        </div>
    </div>
@endsection
