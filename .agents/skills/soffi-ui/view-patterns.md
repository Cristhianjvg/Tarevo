# Soffiweb UI - Patrones Blade

Usar estos patrones al crear o refactorizar vistas principales en Laravel + Blade.
Soffi UI controla apariencia, layout e interactividad: el proyecto no carga
Bootstrap ni ningún otro framework CSS.

## Reglas base

- Usar los patrones autosuficientes de este archivo y adaptar nombres de rutas, variables, campos y textos al proyecto actual.
- Si el proyecto ya tiene vistas migradas a Soffi UI, se pueden usar como contexto visual adicional, pero la skill no depende de rutas concretas.
- La vista no debe incluir `<style>`, `style=""` ni clases locales para componentes visuales.
- Si falta una pieza reusable, crearla en `src/components/<familia>.css` del paquete, publicar assets y luego usarla como `sw-*`.
- El controller prepara filtros, paginación, resúmenes, totales y datos auxiliares. No consultar DB, modelos ni utilidades desde Blade.
- Usar paginación en listados que puedan crecer. Mantener filtros con `appends(request()->query())`.
- Validar antes de terminar: `php artisan view:cache`, `php artisan view:clear`, `git diff --check`.

## Patrón 1: CRUD/Listado

Úsalo para socios, usuarios, categorías, tipos, catálogos y listados administrativos.

```blade
@extends('principal')

@section('title', 'Entidad')

@section('contenido')
<main class="main sw-crud-page">
    <div class="container-fluid sw-crud-container">
        <div class="sw-page-header sw-crud-head">
            <div class="sw-crud-title">
                <h1 class="sw-page-title">Entidad</h1>
                <p class="sw-page-subtitle">Texto breve de administración.</p>
            </div>
            <div class="sw-crud-actions">
                <button class="sw-btn sw-btn-primary sw-btn-sm" type="button" onclick="swOpenModal('abrirmodalEntidad')">
                    <i class="fa fa-plus"></i>
                    <span>Agregar</span>
                </button>
                <a href="{{ route('entidad.pdf', request()->query()) }}" class="sw-btn sw-btn-ghost sw-btn-sm" target="_blank">
                    <i class="fa fa-print"></i>
                    <span>PDF</span>
                </a>
            </div>
        </div>

        <section class="sw-card sw-list-card">
            <div class="sw-list-toolbar">
                {!! Form::open(['url' => 'entidad', 'method' => 'GET', 'autocomplete' => 'off', 'role' => 'search', 'class' => 'sw-list-search']) !!}
                    <div class="sw-list-filter-row">
                        <div class="sw-list-filter-main">
                            <label class="sw-list-search-label" for="buscarTexto">Buscar</label>
                            <div class="sw-input-icon-wrap">
                                <i class="fa fa-search sw-input-icon" aria-hidden="true"></i>
                                <input type="text" name="buscarTexto" id="buscarTexto" class="sw-input sw-input-has-icon" value="{{ $buscarTexto ?? '' }}">
                            </div>
                        </div>
                        <div class="sw-list-filter-control">
                            <label class="sw-list-search-label" for="estado">Estado</label>
                            <select name="estado" id="estado" class="sw-select">
                                <option value="">Todos</option>
                            </select>
                        </div>
                        <div class="sw-list-filter-actions">
                            <button type="submit" class="sw-btn sw-btn-primary sw-btn-sm">
                                <i class="fa fa-search"></i>
                                <span>Filtrar</span>
                            </button>
                        </div>
                    </div>
                {!! Form::close() !!}

                <div class="sw-list-meta">
                    <span class="sw-list-count">{{ $items->total() }} registros</span>
                </div>
            </div>

            <div class="sw-table-wrap sw-list-table-wrap">
                <table class="sw-table sw-table-min-xl">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>
                                    <div class="sw-text-stack">
                                        <span class="sw-table-title">{{ $item->nombre }}</span>
                                        <span class="sw-table-subtitle">{{ $item->codigo }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="sw-badge sw-badge-success">
                                        <i class="fa fa-circle"></i>
                                        Activo
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="sw-table-actions">
                                        <button class="sw-bico sw-bico-edit" type="button" title="Editar" aria-label="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="sw-bico sw-bico-delete" type="button" title="Desactivar" aria-label="Desactivar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <span class="sw-empty-state">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        Sin registros
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
@endsection
```

## Patrón 2: Tabs Con Resumen

Úsalo cuando una pantalla tenga dos secciones del mismo módulo, por ejemplo listado y resumen agrupado.

```blade
<ul class="sw-panel-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" href="#listado" role="tab" aria-selected="true"
           onclick="swActivateTab(event, this)">Listado</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" href="#resumen" role="tab" aria-selected="false"
           onclick="swActivateTab(event, this)">Resumen</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane active sw-panel-tab-pane" id="listado" role="tabpanel">
        <section class="sw-card sw-panel-card sw-list-card">
            <!-- filtros + tabla del patrón CRUD -->
        </section>
    </div>

    <div class="tab-pane sw-panel-tab-pane" id="resumen" role="tabpanel">
        <section class="sw-card sw-panel-card sw-list-card">
            <div class="sw-list-toolbar">
                {!! Form::open(['url' => 'ruta', 'method' => 'GET', 'class' => 'sw-list-search']) !!}
                    <input type="hidden" name="tab" value="resumen">
                    <div class="sw-list-filter-row">
                        <div class="sw-list-filter-main">
                            <label class="sw-list-search-label" for="resumenBuscarTexto">Nombre</label>
                            <div class="sw-input-icon-wrap">
                                <i class="fa fa-search sw-input-icon" aria-hidden="true"></i>
                                <input class="sw-input sw-input-has-icon" name="resumenBuscarTexto" id="resumenBuscarTexto">
                            </div>
                        </div>
                        <div class="sw-list-filter-actions">
                            <button type="submit" class="sw-btn sw-btn-primary sw-btn-sm">
                                <i class="fa fa-search"></i>
                                <span>Filtrar</span>
                            </button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>

            @forelse($resumenItems as $item)
                <div class="sw-report-block">
                    <div class="sw-report-heading">{{ $item->nombre }}</div>
                    <div class="sw-table-wrap">
                        <table class="sw-table">
                            <!-- detalle agrupado -->
                        </table>
                    </div>
                </div>
            @empty
                <span class="sw-empty-state">
                    <i class="fa fa-list" aria-hidden="true"></i>
                    Sin datos para el resumen
                </span>
            @endforelse
        </section>
    </div>
</div>
```

## Patrón 3: Vista Operativa Financiera

Úsalo para pantallas como pagos, liquidaciones, cierres o procesos con selector, resumen, tabla de detalle y modal.

Estructura:

- Header `sw-crud-head`.
- Card de filtro `sw-card sw-list-card` con selector principal y acciones.
- Card de resumen solo si el proceso lo necesita funcionalmente.
- Tabla de detalle en `sw-table-wrap sw-list-table-wrap` con `sw-table sw-table-min-xl`.
- Acciones de fila con `sw-table-actions` y botones icono `sw-bico`. Con 3 o más acciones, por debajo de 1200px queda suelta solo la principal y el resto va al menú `sw-row-menu`: botón con `sw-bico-hide-compact` + copia en el menú con `sw-row-menu-item sw-row-menu-compact-only`. Si la fila no tenía menú, se crea con `sw-row-menu sw-row-menu-compact-only` (solo aparece por debajo de 1200px). Con 1 o 2 acciones no se usa menú.
- Modal `sw-modal-backdrop` + `sw-modal sw-modal-lg` e include de form parcial.

Resumen funcional:

```blade
<section class="sw-card sw-list-card sw-mb-4">
    <div class="sw-list-toolbar">
        <div>
            <h2 class="sw-card-title sw-mb-1">Resumen</h2>
            <p class="sw-card-body sw-mb-0">Datos principales del proceso seleccionado.</p>
        </div>
    </div>

    <div class="sw-row">
        <div class="sw-col-8 sw-mb-3 sw-mb-xl-0">
            <div class="sw-data-grid">
                <div class="sw-data-item">
                    <span class="sw-data-label">Etiqueta</span>
                    <span class="sw-data-value">Valor</span>
                </div>
            </div>
        </div>
        <div class="sw-col-4">
            <div class="sw-total-panel">
                <div class="sw-total-label">Total</div>
                <div class="sw-total-value sw-money">{{ number_format($total, 2) }}</div>
                <p class="sw-total-copy">Detalle secundario</p>
            </div>
        </div>
    </div>
</section>
```

## Patrón 4: Listado interactivo

Usarlo cuando el listado necesite acciones de encabezado, carga visible,
columna fija o varias acciones por fila.

```html
<div class="sw-crud-actions">
    <button type="button" class="sw-btn sw-btn-primary" onclick="swOpenModal('nuevo-modal')">
        Nuevo
    </button>
    <div class="sw-dropdown">
        <button type="button" aria-haspopup="menu" aria-expanded="false"
                aria-controls="acciones" onclick="swToggleDropdown(this)">
            Más acciones
        </button>
        <div id="acciones" class="sw-dropdown-menu sw-dropdown-menu-start" role="menu">
            <button type="button" class="sw-dropdown-item" role="menuitem">Actualizar</button>
        </div>
    </div>
</div>

<div class="sw-card sw-skeleton-card" aria-busy="true" aria-label="Cargando" id="listadoSkeleton">
    <div class="sw-skeleton-layout">
        <div class="sw-skeleton-row"><span class="sw-skeleton sw-skeleton-text"></span></div>
    </div>
</div>

<div id="listadoContent" hidden>
    <div class="sw-table-wrap sw-list-table-wrap">
        <table class="sw-table sw-table-min-xl">
            <thead><tr><th>Nombre</th><th class="sw-table-sticky-col">Acciones</th></tr></thead>
            <tbody><!-- filas Blade --></tbody>
        </table>
    </div>
</div>
```

Al mostrar el contenido, llamar `swSyncTableStickyColumns()`. No copiar
`galLoadEmployees()` ni otras funciones `gal*` de la galería: son demostración,
no API de aplicación. `.sw-table-sticky-col` queda fija horizontalmente en todos los anchos.

## Formularios Parciales En Modal

El parcial se incluye desde el modal de crear y el de editar, así que **siempre**
lleva `$formIdPrefix`: sin él los `id` se duplican y `getElementById` golpea
siempre el modal de crear, dejando el de editar vacío.

```blade
@php($formIdPrefix = $formIdPrefix ?? 'modulo')

<div class="sw-section-block">
    <div class="sw-section-title">Datos principales</div>
    <div class="sw-row">
        <div class="sw-col-4 sw-mb-3">
            <div class="sw-field">
                <label class="sw-label" for="{{ $formIdPrefix }}-campo">
                    Campo
                    <span class="sw-required">*</span>
                </label>
                <input id="{{ $formIdPrefix }}-campo" name="campo"
                       class="sw-input @error('campo') sw-error @enderror" required>
                @error('campo')
                    <small class="sw-hint-error">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="sw-form-actions">
    <button type="button" class="sw-btn sw-btn-ghost sw-btn-sm"
            onclick="swCloseModal(this.closest('.sw-modal-backdrop').id)">
        <i class="fa fa-times"></i>
        <span>Cerrar</span>
    </button>
    <button type="submit" class="sw-btn sw-btn-primary sw-btn-sm">
        <i class="fa fa-save"></i>
        <span>Grabar</span>
    </button>
</div>
```

Y desde la vista:

```blade
<div class="sw-modal-backdrop" id="abrirmodalModulo">
    <div class="sw-modal sw-modal-lg" role="dialog" aria-modal="true">
        <div class="sw-modal-title">
            <div>
                <div>Agregar registro</div>
                <p class="sw-card-body">Descripción corta.</p>
            </div>
            <button type="button" class="sw-modal-close" onclick="swCloseModal('abrirmodalModulo')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form action="{{ route('modulo.store') }}" method="post" class="needs-validation" novalidate>
            @csrf
            @php($formIdPrefix = 'modulo-create')
            @include('modulo.form')
        </form>
    </div>
</div>
```

## Sin Framework CSS

El proyecto **no carga Bootstrap, Popper ni ningún framework**. Sus clases no
existen: escribirlas no da error, simplemente no aplican nada y el layout queda
roto en silencio.

| Necesidad        | Usar                                                              |
| ---------------- | ----------------------------------------------------------------- |
| Grid             | `sw-row` + `sw-col-4/6/8/12`                                       |
| Espaciado        | `sw-mb-*`, `sw-mt-*`, `sw-py-*`, `sw-px-4`, `sw-p-0`               |
| Flex             | `sw-d-flex`, `sw-flex-column`, `sw-justify-content-*`, `sw-align-items-*` |
| Responsive       | `sw-flex-md-row`, `sw-align-items-md-center`, `sw-mb-lg-0`         |
| Alineación texto | `sw-text-left/center/right` (los sin prefijo están aliasados)      |
| Modales          | `sw-modal-backdrop` + `swOpenModal()` / `swCloseModal()`           |
| Tabs             | `sw-panel-tabs` + `swActivateTab(event, this)`                     |
| Collapse         | `data-toggle="collapse"` + `data-target` (lo maneja `soffi-ui.js`) |

Además:

- No usar `.btn`, `.card`, `.table`, `.form-control`, `.badge` como estilo principal.
- No crear CSS local para arreglar componentes: si falta algo, agregarlo a
  `src/components/` del paquete y reconstruir.
- No poner cards dentro de cards; usar `sw-data-grid`, `sw-total-panel` o `sw-report-block`.
