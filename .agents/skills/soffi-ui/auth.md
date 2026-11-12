# Soffiweb UI — Auth / Entry Screens

Patrón para pantallas sin sidebar/topbar: login, selección de empresa, recuperar contraseña, cualquier paso intermedio de un flujo de acceso. Reemplaza cualquier CSS custom con gradientes/hex hardcodeado que existiera antes en estas vistas — están sujetas a las mismas reglas que el resto del sistema (sin gradientes decorativos, sin hex, tokens `sw-*` únicamente).

## Layout base

Igual que `layout.md`, pero sin topbar/sidebar — el `<body>` renderiza directo el contenido de la pantalla:

```html
<!DOCTYPE html>
<html lang="es" id="root" data-theme="light">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>{{ config('app.name') }} — @yield('title', 'Acceso')</title>

  {{-- Anti-flicker: aplicar tema guardado ANTES del primer paint --}}
  <script>
    (function () {
      var t = localStorage.getItem('theme');
      if (t) document.documentElement.dataset.theme = t;
    })();
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>
  <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('vendor/soffi-ui/soffi-ui.css') }}" rel="stylesheet">
</head>
<body>
  @yield('contenido')
</body>
</html>
```

No hay toggle de tema en estas pantallas (el usuario aún no tiene sesión) — solo se respeta el tema ya guardado en `localStorage` de una sesión anterior en el mismo navegador.

## Componentes

- `.sw-auth-shell` — wrapper de página completa, centra el card vertical y horizontalmente.
- `.sw-auth-card` — combinar siempre con `.sw-card` (`class="sw-card sw-auth-card"`). Usa `background: var(--b2)` en vez del `var(--b1)` de `.sw-card` normal, para contrastar contra el fondo de página (`html` ya trae `var(--b1)` por `base.css`). Max-width 420px.
- `.sw-auth-brand` + `.sw-auth-logo` — logo centrado arriba del card.
- `.sw-auth-title` — combinar con `.sw-card-title` para el heading centrado. El heading es el **nombre del sistema** (`config('app.name')` o equivalente), no una etiqueta genérica de acción como "Iniciar sesión" — el usuario ya sabe que está entrando a algo, necesita saber a qué.
- `.sw-auth-subtitle` — combinar con `.sw-hint` para el texto de apoyo centrado.
- `.sw-auth-submit` — combinar con `.sw-btn.sw-btn-primary` para el botón principal a todo el ancho.
- `.sw-auth-remember` — envuelve un `<input type="checkbox" class="sw-checkbox-input">` (label completo, click en cualquier parte marca el check). Sigue siendo un `<input>` nativo a propósito: funciona sin JS y es crítico en el camino de login. `.sw-checkbox-input` es el checkbox estándar del sistema (`appearance: none` + estilo propio en `forms.css`) — usarlo en cualquier checkbox de la app, no queda ningún checkbox sin la clase.

Formulario: mismos componentes que cualquier form (`layout.md`/`components.md`) — `.sw-field`, `.sw-label`, `.sw-input`, `.sw-select`, `.sw-hint-error`, `.sw-input-icon-wrap` + `.sw-input-icon` + `.sw-input-has-icon` para inputs con ícono. Errores generales de formulario (no de un campo puntual): `.sw-alert.sw-alert-error`.

## Ejemplo — login

```blade
@extends('auth.contenido')

@section('title', 'Iniciar sesión')

@section('login')
<div class="sw-auth-shell">
    <div class="sw-card sw-auth-card">
        <div class="sw-auth-brand">
            <img src="{{ asset('img/Logo.png') }}" alt="{{ config('app.name') }}" class="sw-auth-logo">
        </div>
        <h1 class="sw-card-title sw-auth-title">{{ config('app.name') }}</h1>
        <p class="sw-hint sw-auth-subtitle">Ingresa tus credenciales para continuar.</p>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="sw-field">
                <label class="sw-label" for="usuario">Usuario</label>
                <div class="sw-input-icon-wrap">
                    <i class="fa fa-user sw-input-icon"></i>
                    <input type="text" class="sw-input sw-input-has-icon @error('usuario') sw-error @enderror" name="usuario" id="usuario" required autofocus>
                </div>
                {!! $errors->first('usuario', '<span class="sw-hint-error">:message</span>') !!}
            </div>

            <div class="sw-field">
                <label class="sw-label" for="password">Contraseña</label>
                <div class="sw-input-icon-wrap">
                    <i class="fa fa-lock sw-input-icon"></i>
                    <input type="password" class="sw-input sw-input-has-icon @error('password') sw-error @enderror" name="password" id="password" required>
                </div>
                {!! $errors->first('password', '<span class="sw-hint-error">:message</span>') !!}
            </div>

            <label class="sw-auth-remember">
                <input type="checkbox" class="sw-checkbox-input" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                Recordarme
            </label>

            <button type="submit" class="sw-btn sw-btn-primary sw-auth-submit">
                <i class="fa fa-right-to-bracket"></i> Iniciar sesión
            </button>
        </form>
    </div>
</div>
@endsection
```

Referencia real: `resources/views/auth/login.blade.php` y `resources/views/auth/soffcore-company-selection.blade.php` en SoffCaja (esta última muestra el patrón con `.sw-select` y `.sw-alert.sw-alert-error` para errores generales del formulario).

## Prohibido en estas pantallas

| ❌ Nunca | ✅ Siempre |
|---|---|
| Gradiente de fondo o de header del card | Fondo plano `var(--b2)` vía `.sw-auth-card` |
| Hex hardcodeado (`#0f2c4c`, `#21384d`...) | Tokens `var(--p)`, `var(--bc)`, `var(--bc2)` |
| `<style>` local con clases nuevas por vista | Componente en `src/components/` del paquete, luego consumido en la vista |
| `btn btn-success`, `form-control`, `alert alert-danger` (Bootstrap puro) | `sw-btn sw-btn-primary`, `sw-input`/`sw-select`, `sw-alert sw-alert-error` |
| Meta/title genéricos heredados de plantilla (`<title>Proyecto</title>`) | `{{ config('app.name') }}` real |
