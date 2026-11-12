# Soffiweb UI — Base Layout

## Estructura HTML

```html
<!DOCTYPE html>
<html lang="es" id="root" data-theme="light">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }} — @yield('title')</title>

  {{-- Anti-flicker: aplicar tema guardado ANTES del primer paint.
       soffi-ui.js se carga al final del body; sin esto se ve un
       flash claro→oscuro en cada carga de página. --}}
  <script>
    (function () {
      var t = localStorage.getItem('theme');
      if (t) document.documentElement.dataset.theme = t;
    })();
  </script>

  {{-- Fuentes e iconos: FA6 vendorizado localmente, sin CDN ni v4-shims --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>
  <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet"/>

  {{-- Soffi UI core: tokens + base + componentes --}}
  <link href="{{ asset('vendor/soffi-ui/soffi-ui.css') }}" rel="stylesheet">
</head>
<body>

  <div class="sw-app">

    {{-- ── TOPBAR ── --}}
    {{-- Lado izquierdo: hamburguesa (móvil) + logo + empresa + período --}}
    {{-- Lado derecho: toggle tema + menú usuario con avatar, nombre, rol --}}
    <header class="sw-topbar">
      <div class="sw-topbar-brand">

        {{-- Botón hamburguesa — solo visible en móvil --}}
        <button type="button" class="sw-btn sw-btn-ghost sw-btn-sm sw-sidebar-toggle"
                aria-controls="sw-sidebar" aria-expanded="false" aria-label="Abrir menú"
                onclick="swToggleSidebar(this)">
          <i class="fa-solid fa-bars"></i>
        </button>

        {{-- Logo de la empresa/tenant activo, no el logo de la plataforma.
             Fallback a icono cuando la empresa no tiene logo cargado --}}
        <span class="sw-topbar-app">
          @if ($headerCompanyLogoUrl)
            <img src="{{ $headerCompanyLogoUrl }}" alt="{{ $headerCompanyName }}" class="sw-topbar-logo">
          @else
            <span class="sw-topbar-logo-fallback" aria-hidden="true">
              <i class="fa-solid fa-building-columns"></i>
            </span>
          @endif
        </span>

        {{-- Empresa y período activo --}}
        <div class="sw-topbar-tenant">
          <span class="sw-topbar-company" title="{{ $headerCompanyName }}">
            {{ $headerCompanyName }}
          </span>
          <span class="sw-topbar-period" title="{{ $headerPeriodLabel }}">
            <i class="fa-regular fa-calendar"></i> {{ $headerPeriodLabel }}
          </span>
        </div>

      </div>

      <div class="sw-topbar-actions">

        {{-- Toggle de tema --}}
        <button type="button" class="sw-btn sw-btn-ghost sw-btn-sm"
                aria-label="Cambiar tema" onclick="swToggleTheme()">
          <i class="fa-solid fa-circle-half-stroke"></i>
        </button>

        {{-- Menú de usuario --}}
        <div class="sw-user-menu">
          <button type="button" class="sw-user-trigger"
                  aria-haspopup="true" aria-expanded="false"
                  aria-controls="sw-user-dropdown"
                  onclick="swToggleUserMenu(this)">

            {{-- Avatar con fallback --}}
            @if($hasCustomAvatar)
              <span class="sw-user-avatar sw-user-avatar-photo" aria-hidden="true">
                <img src="{{ $avatarUrl }}" class="sw-user-avatar-img" alt="">
                <i class="fa-solid fa-user sw-user-avatar-fallback"></i>
              </span>
            @else
              <span class="sw-user-avatar" aria-hidden="true">
                <i class="fa-solid fa-user"></i>
              </span>
            @endif

            <span class="sw-user-name">{{ $displayName }}</span>
            <i class="fa-solid fa-angle-down sw-user-arrow"></i>
          </button>

          {{-- Dropdown usuario --}}
          <div class="sw-user-dropdown" id="sw-user-dropdown">
            <div class="sw-user-role">
              <span class="sw-user-role-label">Rol</span>
              <span class="sw-user-role-value">{{ $roleLabel }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="sw-user-action">
                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
              </button>
            </form>
          </div>
        </div>

      </div>
    </header>

    <div class="sw-body">

      {{-- ── SIDEBAR ── --}}
      <nav class="sw-sidebar" id="sw-sidebar" aria-label="Navegación principal">

        <div class="sw-sb-brand">
          <div class="sw-sb-brand-name">{{ config('app.name') }}</div>
          <div class="sw-sb-brand-sub">Sistema de gestión</div>
        </div>

        <div class="sw-sb-group">Principal</div>
        <a href="{{ route('dashboard') }}"
           class="sw-sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="fa-solid fa-house"></i> Dashboard
        </a>

        {{-- Ejemplo de submenú --}}
        <div class="sw-sb-group">Módulos</div>
        <button type="button"
                class="sw-sb-link sw-sb-toggle {{ request()->routeIs('reportes.*') ? 'open' : '' }}"
                aria-expanded="{{ request()->routeIs('reportes.*') ? 'true' : 'false' }}"
                aria-controls="sw-menu-reportes"
                onclick="swToggleSub(this)">
          <i class="fa-solid fa-chart-bar"></i> Reportes
          <i class="fa-solid fa-chevron-right sw-arrow"></i>
        </button>
        <div id="sw-menu-reportes"
             class="sw-sb-sub {{ request()->routeIs('reportes.*') ? 'open' : '' }}">
          <a href="{{ route('reportes.index') }}"
             class="sw-sb-sub-link {{ request()->routeIs('reportes.index') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i> Resumen
          </a>
        </div>

        <div class="sw-sb-group">Cuenta</div>
        <a href="{{ route('profile') }}"
           class="sw-sb-link {{ request()->routeIs('profile') ? 'active' : '' }}">
          <i class="fa-solid fa-circle-user"></i> Mi perfil
        </a>
        <form action="{{ route('logout') }}" method="POST" class="sw-sb-form">
          @csrf
          <button type="submit" class="sw-sb-link sw-sb-action">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
          </button>
        </form>

      </nav>

      {{-- Backdrop para cerrar el sidebar en móvil --}}
      <button type="button" class="sw-sidebar-backdrop"
              aria-label="Cerrar menú" onclick="swCloseSidebar()"></button>

      {{-- ── MAIN ── --}}
      <main class="sw-main sw-fade-in">
        @yield('content')
      </main>

    </div>

    {{-- ── FOOTER ── --}}
    {{-- Tercer hijo flex de .sw-app: queda pegado abajo, no duplica scroll con .sw-main --}}
    <footer class="sw-footer">
      <span class="sw-footer-brand">
        {{-- Marca de la plataforma (Soffiweb), no la del tenant — esa va en el topbar --}}
        <a href="https://soffiweb.ec" target="_blank" title="Soffiweb">
          <img src="{{ asset('img/LogoSoffiweb.png') }}" alt="Soffiweb" class="sw-footer-logo">
        </a>
        <a href="{{ url('/') }}" target="_blank">{{ url('/') }}</a> &copy; {{ date('Y') }}
      </span>
      <span class="sw-footer-links">
        {{-- Telefono/WhatsApp: SIEMPRE incluir el/los numero(s) reales del proyecto — nunca opcional, nunca inventado --}}
        <a href="https://wa.me/593000000000" target="_blank" title="WhatsApp">
          <i class="fa-brands fa-whatsapp"></i> +593 00 000 0000
        </a>
        {{-- Redes sociales: OPCIONAL, solo si el proyecto las tiene. Solo icono, sin texto ni URL visible --}}
        <a href="#" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" target="_blank" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
      </span>
    </footer>
  </div>

  <script src="{{ asset('vendor/soffi-ui/soffi-ui.js') }}"></script>
  @stack('scripts')
</body>
</html>
```

---

## Datos del header — vienen del backend, nunca desde Blade

| Variable             | Descripción                                     |
| -------------------- | ----------------------------------------------- |
| `$headerCompanyName` | Empresa o tenant activo                         |
| `$headerCompanyLogoUrl` | URL del logo de la empresa/tenant, o `null` si no tiene uno cargado (usar `.sw-topbar-logo-fallback` + icono en ese caso) |
| `$headerPeriodLabel` | Período vigente (ej. `2025-01-01 – 2025-12-31`) |
| `$displayName`       | Nombre del usuario autenticado                  |
| `$roleLabel`         | Rol o cargo del usuario                         |
| `$hasCustomAvatar`   | `bool` — si tiene foto de perfil                |
| `$avatarUrl`         | URL de la foto de perfil                        |

Resolverlos en controller, `View::composer` o middleware. **Nunca consultar DB en Blade.**

---

## Responsabilidades por zona

| Zona    | Qué contiene                                                    |
| ------- | --------------------------------------------------------------- |
| Topbar  | Logo de la empresa/tenant (no de Soffiweb) + nombre + período (izquierda) · tema + usuario (derecha) |
| Sidebar | Navegación agrupada con submenús · sección Cuenta al final      |
| Main    | `@yield('content')` — el contenido de cada vista                |
| Footer  | Copyright / contacto — franja fina, siempre pegada abajo         |

---

## Contenido del footer (reglas)

- **Izquierda**: logo de Soffiweb (`.sw-footer-brand` + `.sw-footer-logo`, enlaza a `https://soffiweb.ec`) — es la marca de la plataforma, va aquí y NUNCA en el topbar (ese espacio es del tenant). Junto a eso, URL de la aplicación via `url('/')` — nunca un dominio hardcodeado — más copyright con año dinámico `date('Y')`.
- **Derecha — teléfono/WhatsApp**: **SIEMPRE** incluir al menos un número de contacto real del proyecto. No es opcional, no se omite "por si acaso". Usar el dato real que entregue el cliente/proyecto; nunca inventar un número de ejemplo en el resultado final.
- **Derecha — redes sociales**: opcional. Si el proyecto tiene Instagram/Facebook/etc., agregarlas **solo como ícono** (`fa-brands fa-instagram`, `fa-brands fa-facebook`, ...) sin texto ni URL visible junto al ícono.
- **Tamaño**: intencionalmente chico — es la franja de menor jerarquía de toda la pantalla (`padding: 7px 20px`, `font-size: 11px`, logo `14px` de alto en `.sw-footer`/`.sw-footer-logo`). No agrandarlo "para que se note más"; si compite visualmente con el contenido de `.sw-main`, está mal.
- Agrupar teléfono(s) + redes en un único `<span class="sw-footer-links">` (flex con gap, ya viene de soffi-ui).

---

## Cómo agregar secciones al sidebar

### Enlace simple

```html
<a
    href="{{ route('x.index') }}"
    class="sw-sb-link {{ request()->routeIs('x.*') ? 'active' : '' }}"
>
    <i class="fa-solid fa-icon-name"></i> Nombre
</a>
```

### Submenú expandible

```html
<button
    type="button"
    class="sw-sb-link sw-sb-toggle {{ request()->routeIs('x.*') ? 'open' : '' }}"
    aria-expanded="{{ request()->routeIs('x.*') ? 'true' : 'false' }}"
    aria-controls="sw-menu-x"
    onclick="swToggleSub(this)"
>
    <i class="fa-solid fa-icon"></i> Nombre del módulo
    <i class="fa-solid fa-chevron-right sw-arrow"></i>
</button>
<div
    id="sw-menu-x"
    class="sw-sb-sub {{ request()->routeIs('x.*') ? 'open' : '' }}"
>
    <a
        href="{{ route('x.sub1') }}"
        class="sw-sb-sub-link {{ request()->routeIs('x.sub1') ? 'active' : '' }}"
    >
        <i class="fa-solid fa-file-lines"></i> Subopción 1
    </a>
    <a
        href="{{ route('x.sub2') }}"
        class="sw-sb-sub-link {{ request()->routeIs('x.sub2') ? 'active' : '' }}"
    >
        <i class="fa-solid fa-list"></i> Subopción 2
    </a>
</div>
```
