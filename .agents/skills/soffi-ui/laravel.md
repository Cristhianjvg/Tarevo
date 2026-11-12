# Soffi UI — Laravel + Blade

Integración de Soffi UI en Laravel + Blade (sin Livewire). Los tokens,
componentes y reglas de compatibilidad están en [SKILL.md](SKILL.md) y sus
capítulos de CSS.

---

## Stack

| Capa           | Tecnología                                       |
| -------------- | ------------------------------------------------ |
| Framework      | Laravel 13.x                                     |
| CSS            | Soffi UI (`soffi-ui.css`) — sin framework externo |
| Vistas         | Blade templates                                   |
| Interactividad | `soffi-ui.js` (JS nativo, sin jQuery)            |
| Iconos         | Font Awesome 6                                    |
| Tipografía     | Inter (Google Fonts)                              |
| Modales        | `sw-modal` + `swOpenModal()` / `swCloseModal()`  |
| Auth           | `laravel/ui` o manual                             |

**jQuery** solo se carga si el proyecto usa select2. No es dependencia de Soffi UI.

---

## Contrato de consistencia visual

- Fuente única: el paquete `soffiweb/soffi-ui` (`src/tokens/`, `src/base/`,
  `src/components/`). `public/vendor/soffi-ui/` es **salida de build**; editarla
  a mano se pierde en el siguiente `vendor:publish --tag=soffi-ui --force`.
- No crear CSS de componente dentro de cada vista. Las vistas solo componen
  clases `sw-*` existentes.
- Si falta un componente o utilidad reusable, agregarlo primero al paquete
  (`src/base/utilities.css` para utilidades, `src/components/<familia>.css` para
  componentes), reconstruir con `php scripts/build.php`, publicar una versión
  nueva y recién ahí usarlo en la app.
- Un `<style>` dentro de una vista se justifica solo para algo genuinamente
  específico de esa pantalla, con clase prefijada por vista
  (ej. `.cierre-ahorros-grid`).

## Instalación en un proyecto nuevo

1. Instalar el paquete y publicar los assets — ver "Instalación y actualización"
   en [SKILL.md](SKILL.md). Nunca copiar `soffi-ui/` a la raíz de la app.
2. Font Awesome 6 en `public/vendor/fontawesome/`.
3. Layout: usar `@extends('soffi-ui::layouts.panel')` con `<x-sw-sidebar>`
   (capa Blade del paquete). El layout manual de más abajo es el contrato de
   markup para las apps que ya tienen el suyo propio.
4. Entregar desde backend `$headerCompanyName`, `$headerPeriodLabel`,
   `$displayName`, `$roleLabel` (controller, middleware o `View::composer`;
   nunca consultar DB desde Blade).

---

## Patrones Blade estándar

Para crear o refactorizar vistas principales, leer y aplicar
[view-patterns.md](view-patterns.md).

## Regla de utilidades

Soffi UI **no** carga Bootstrap, así que sus utilidades no existen. Escribir
`row`, `col-lg-6`, `mb-4`, `d-flex`, `justify-content-between`, `h-100`,
`text-right`, `font-weight-bold` o `w-100` no da error: simplemente no aplica
nada y el layout queda roto de forma silenciosa (columnas apiladas a ancho
completo, cabeceras sin alinear, márgenes ausentes).

Usar siempre el equivalente `sw-*`. La lista completa está en la sección
"Utilidades" de [components.md](components.md).

Única excepción: `text-left`, `text-center` y `text-right` están aliasados por
compatibilidad con vistas heredadas.

## Regla para modales

- `sw-modal-backdrop` + `sw-modal`, abiertos con `swOpenModal(id)` y cerrados
  con `swCloseModal(id)`. Nunca `data-toggle="modal"` / `data-dismiss="modal"`.
- `swOpenModal()` reubica el modal en `<body>` porque `.sw-main` crea un
  stacking context propio y atraparía un `position: fixed` bajo el topbar.
- Un formulario incluido en dos modales (crear/editar) **debe** usar
  `$formIdPrefix`; si no, `getElementById` siempre golpea el de crear y el modal
  de editar abre vacío. Patrón completo en [js.md](js.md).

## Regla de validación

Formularios con `class="needs-validation" novalidate`. `soffi-ui.js` valida al
enviar y pinta `sw-error` + `sw-hint-error` con mensajes en español. Para
mensajes precisos en `pattern`, usar el atributo `title` del input.

## Regla de densidad visual

No agregar métricas, totales o bloques KPI por defecto en vistas CRUD/listado.
Solo se renderizan si el usuario lo solicita explícitamente.

## Regla de marca en topbar

En el bloque izquierdo del topbar usar logo de la marca/empresa si existe asset
disponible. No dejar solo `config('app.name')` como texto plano salvo que el
usuario lo pida.

## Regla de no redundancia de empresa

El nombre de la empresa o tenant actual debe mostrarse en el header/topbar y no
repetirse dentro del contenido de la vista.

- No renderizar `razonsocial`, nombre comercial o label de empresa encima de
  tablas, cards, filtros o resúmenes.
- No duplicar el contexto tenant dentro de encabezados internos si ya está
  visible en el topbar.
- Solo mostrar datos de empresa dentro del contenido cuando sean parte funcional
  del documento: reportes imprimibles, PDFs, comprobantes.

## Regla para tabs integradas a panel

Cuando una vista CRUD necesite alternar entre 2 o más secciones del mismo
contexto, usar tabs compactas integradas al panel; no apilar bloques.

- Tabs propias: `swActivateTab(event, this)` en el `onclick` del link.
- Alineadas a la izquierda, ocupando solo el ancho necesario.
- Activa con fondo `var(--p)` y texto `var(--btn-text)`; inactivas `var(--b2)`
  con texto `var(--bc2)`.
- El panel debe verse unido a las tabs: radios superiores en tabs, inferiores en
  el panel, sin separación vertical.
- Evitar tabs de ancho completo salvo requerimiento explícito.

```html
<ul class="sw-panel-tabs" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" href="#panel-1" role="tab"
       aria-selected="true" onclick="swActivateTab(event, this)">Pestaña 1</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" href="#panel-2" role="tab"
       aria-selected="false" onclick="swActivateTab(event, this)">Pestaña 2</a>
  </li>
</ul>

<div class="tab-content">
  <div class="tab-pane active sw-panel-tab-pane" id="panel-1" role="tabpanel">
    <section class="sw-card sw-panel-card sw-list-card">...</section>
  </div>

  <div class="tab-pane sw-panel-tab-pane" id="panel-2" role="tabpanel">
    <section class="sw-card sw-panel-card sw-list-card">...</section>
  </div>
</div>
```

## Listados interactivos actuales

- Encapsular tablas en `sw-table-wrap sw-list-table-wrap`.
- Para columnas de acciones que deben permanecer visibles en escritorio, usar
  `.sw-table-sticky-col` en `th` y `td`; permanece fija horizontalmente en
  todos los anchos y toma solo el ancho de su contenido. El JS agrega el
  indicador `.is-table-sticky-displaced` automáticamente.
- Cuando una fila supera cuatro acciones, usar `.sw-row-menu` y
  `swToggleRowMenu(this)`. El menú se porta a `<body>` al abrirse; no agregar
  z-index ni posicionamiento local.
- Para acciones del encabezado usar `.sw-dropdown` y `swToggleDropdown(this)`.
- Para cargas simuladas o respuestas asíncronas usar `.sw-skeleton-card` y
  ocultar el contenido real hasta terminar; conservar `aria-busy` y un nombre
  accesible.
- Para una creación en modal, usar `sw-modal-lg`, `sw-field`, `sw-input` o
  `sw-select`, `sw-form-actions` y `needs-validation novalidate` cuando aplique.

---

## Contrato de markup del layout (`layouts/app.blade.php`)

Referencia del chrome para las apps que mantienen su layout propio. En un
proyecto nuevo usar la capa Blade del paquete (`soffi-ui::layouts.panel`) en vez
de copiar esto.

```html
<!DOCTYPE html>
<html lang="es" id="root" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }} — @yield('title')</title>

  {{-- Restaura el tema antes de pintar, para evitar flash --}}
  <script>
    (function () {
      var t = localStorage.getItem('theme');
      if (t) document.documentElement.dataset.theme = t;
    })();
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>
  <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('vendor/soffi-ui/soffi-ui.css') }}?v={{ filemtime(public_path('vendor/soffi-ui/soffi-ui.css')) }}" rel="stylesheet">
</head>
<body>

  <div class="sw-app">
    <header class="sw-topbar">
      <div class="sw-topbar-brand">
        <button type="button" class="sw-btn sw-btn-ghost sw-btn-sm sw-sidebar-toggle"
                aria-controls="sw-sidebar" aria-expanded="false" aria-label="Abrir menú"
                onclick="swToggleSidebar(this)">
          <i class="fa-solid fa-bars"></i>
        </button>
        <span class="sw-topbar-app">
          <img src="{{ asset('img/LogoSoffiweb.png') }}" alt="{{ config('app.name') }}" class="sw-topbar-logo">
        </span>
        <div class="sw-topbar-tenant">
          <span class="sw-topbar-company" title="{{ $headerCompanyName }}">{{ $headerCompanyName }}</span>
          <span class="sw-topbar-period" title="{{ $headerPeriodLabel }}">
            <i class="fa-regular fa-calendar"></i> {{ $headerPeriodLabel }}
          </span>
        </div>
      </div>

      <div class="sw-topbar-actions">
        <button type="button" class="sw-btn sw-btn-ghost sw-btn-sm" aria-label="Cambiar tema"
                onclick="swToggleTheme()">
          <i class="fa-solid fa-circle-half-stroke"></i>
        </button>

        <div class="sw-user-menu">
          <button type="button" class="sw-user-trigger" aria-haspopup="true" aria-expanded="false"
                  aria-controls="sw-user-dropdown" onclick="swToggleUserMenu(this)">
            <span class="sw-user-avatar" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
            <span class="sw-user-name">{{ $displayName }}</span>
            <i class="fa-solid fa-angle-down sw-user-arrow"></i>
          </button>
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
      @include('layouts.sidebar')
      <button type="button" class="sw-sidebar-backdrop" aria-label="Cerrar menú"
              onclick="swCloseSidebar()"></button>

      <main class="sw-main sw-fade-in">
        @yield('content')
      </main>
    </div>

    {{-- Footer: tercer hijo flex de .sw-app, siempre pegado abajo --}}
    <footer class="sw-footer">
      <span><a href="{{ url('/') }}" target="_blank">{{ url('/') }}</a> &copy; {{ date('Y') }}</span>
      <span class="sw-footer-links">
        {{-- Telefono/WhatsApp: SIEMPRE incluir el/los numero(s) reales del proyecto --}}
        <a href="https://wa.me/593000000000" target="_blank" title="WhatsApp">
          <i class="fa-brands fa-whatsapp"></i> +593 00 000 0000
        </a>
        {{-- Redes sociales: OPCIONAL, solo si el proyecto las tiene. Solo icono --}}
        <a href="#" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" target="_blank" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
      </span>
    </footer>
  </div>

  {{-- @stack('scripts') va antes de las librerias que el JS de vista pueda
       necesitar solo si esas vistas usan $(document).ready; en la practica
       conviene: jQuery -> stack -> resto. --}}
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  @stack('scripts')
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <script src="{{ asset('vendor/soffi-ui/soffi-ui.js') }}?v={{ filemtime(public_path('vendor/soffi-ui/soffi-ui.js')) }}"></script>
</body>
</html>
```

---

## Sidebar Blade (`layouts/sidebar.blade.php`)

```html
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

  <div class="sw-sb-group">Módulos</div>

  <a href="{{ route('activos.index') }}"
     class="sw-sb-link {{ request()->routeIs('activos.*') ? 'active' : '' }}">
    <i class="fa-solid fa-boxes-stacked"></i> Activos
  </a>

  <button type="button" class="sw-sb-link sw-sb-toggle {{ request()->routeIs('reportes.*') ? 'open' : '' }}"
          aria-expanded="{{ request()->routeIs('reportes.*') ? 'true' : 'false' }}"
          aria-controls="sw-menu-reportes" onclick="swToggleSub(this)">
    <i class="fa-solid fa-chart-bar"></i> Reportes
    <i class="fa-solid fa-chevron-right sw-arrow"></i>
  </button>
  <div id="sw-menu-reportes" class="sw-sb-sub {{ request()->routeIs('reportes.*') ? 'open' : '' }}">
    <a href="{{ route('reportes.responsable') }}"
       class="sw-sb-sub-link {{ request()->routeIs('reportes.responsable') ? 'active' : '' }}">
      <i class="fa-solid fa-user"></i> Por responsable
    </a>
    <a href="{{ route('reportes.departamento') }}"
       class="sw-sb-sub-link {{ request()->routeIs('reportes.departamento') ? 'active' : '' }}">
      <i class="fa-solid fa-building"></i> Por departamento
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
```

---

## Prohibido

| ❌ Nunca                                        | ✅ Siempre                                  |
| ----------------------------------------------- | ------------------------------------------- |
| `row`, `col-lg-*`, `col-md-*`                   | `sw-row`, `sw-col-4/6/8/12` (`sw-col-xs-6` para 2 por fila en celular) |
| `mb-*`, `mt-*`, `py-*`, `px-*`, `p-0`           | `sw-mb-*`, `sw-mt-*`, `sw-py-*`, `sw-p-0`   |
| `d-flex`, `flex-column`, `justify-content-*`    | `sw-d-flex`, `sw-flex-column`, `sw-justify-content-*` |
| `h-100`, `w-100`, `text-muted`, `font-weight-bold` | `sw-h-100`, `sw-text-muted`, `sw-font-bold` |
| `data-toggle="modal"`, `data-dismiss="modal"`   | `swOpenModal()` / `swCloseModal()`          |
| `.modal`, `.modal-dialog`, `.modal-body`        | `sw-modal-backdrop`, `sw-modal`             |
| Clases `btn-*`, `badge-*` de BS4                | `sw-btn sw-btn-*`, `sw-badge sw-badge-*`    |
| Clases `table`, `table-striped` de BS4          | `sw-table-wrap` + `sw-table`                |
| Clases `alert alert-*` de BS4                   | `sw-alert sw-alert-*`                       |
| Clase `card` de BS4                             | `sw-card`                                   |
| Cargar `bootstrap.js` / `popper.js`             | `soffi-ui.js`                               |
| Editar `public/vendor/soffi-ui/*`               | Editar `src/` del paquete y reconstruir     |
| Copiar `soffi-ui/` dentro de la app             | `composer require soffiweb/soffi-ui`        |
| Form incluido 2 veces con ids fijos             | `$formIdPrefix`                             |
| jQuery para lógica de negocio                   | JS nativo; jQuery solo para select2         |
