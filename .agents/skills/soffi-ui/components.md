# Soffiweb UI — Components (CSS)

Regla de uso: no introducir bloques `.sw-metric` o `.sw-metrics-grid` por defecto en pantallas CRUD/listado. Usarlos solo si el usuario pide métricas/totales.

`.sw-btn-secondary` fue removido de soffi-ui (color `--s` confundía con el primary). Para acciones no primarias (exportar, filtrar, cancelar) usar siempre `.sw-btn-ghost`.

## Accordion visual

Usar `.sw-accordion` con un botón accesible y un panel `.collapse`. El JS
existente de `swToggleCollapse()` respeta `data-parent` y cierra los hermanos.

```blade
<div id="detalle-acordeon" class="sw-accordion">
    <div class="sw-accordion-item">
        <button type="button" class="sw-accordion-trigger"
                data-toggle="collapse" data-target="#detalle-general"
                aria-controls="detalle-general" aria-expanded="false">
            <span>Datos generales</span>
            <span class="sw-accordion-trigger-icon" aria-hidden="true">⌄</span>
        </button>
        <div id="detalle-general" class="sw-accordion-panel collapse"
             data-parent="#detalle-acordeon">
            <div class="sw-accordion-panel-inner">Contenido.</div>
        </div>
    </div>
</div>
```

## Campo de búsqueda con selector — `.sw-search-field`

Un solo campo con el selector de "buscar por" pegado a la izquierda del buscador.

```html
<div class="sw-search-field">
    <select name="campo" class="sw-select sw-search-field-select" aria-label="Buscar por">
        <option value="nombre">Nombre</option>
        <option value="documento">Documento</option>
    </select>
    <div class="sw-input-icon-wrap sw-search-field-input">
        <i class="fa-solid fa-search sw-input-icon" aria-hidden="true"></i>
        <input type="text" name="buscarTexto" class="sw-input sw-input-has-icon"
               placeholder="Escribe lo que buscas">
    </div>
</div>
```

## Filtros plegables de listado

Por debajo de 576px los filtros se esconden detrás de un botón "Filtros"; desde
576px quedan en la fila de siempre. Usa el collapse de la librería con `data-sw-collapse` (igual que `data-toggle="collapse"`, pero sin chocar con el JS de Bootstrap en las apps que todavía lo cargan).

```html
<div class="sw-list-filter-row">
    <div class="sw-list-filter-main">...buscador...</div>

    <button type="button" class="sw-btn sw-btn-light sw-list-filters-toggle"
            data-sw-collapse data-target="#filtros"
            aria-controls="filtros" aria-expanded="false">
        <span>Filtros <span class="sw-list-filters-count">2</span></span>
        <i class="fa-solid fa-chevron-down sw-list-filters-chevron" aria-hidden="true"></i>
    </button>

    <div id="filtros" class="sw-list-filters-panel collapse">
        <div class="sw-list-filter-control sw-list-filter-control-sm">...</div>
    </div>

    <div class="sw-list-filter-actions">...botón Buscar...</div>
</div>
```

Con filtros activos, abrir el panel desde el servidor (`collapse show` y
`aria-expanded="true"`) para que se vea qué está aplicado.

## SweetAlert2

El override `vendor-sweetalert2.css` ya está incluido en `soffi-ui.css`. Cargar
la hoja de Soffi UI después de SweetAlert2; no crear overrides por aplicación.
La API de `Swal.fire()` se conserva y hereda tokens de tema, botones, inputs,
iconos, toast, foco y `prefers-reduced-motion`.

## Familias canónicas

- Página: `.sw-crud-page`, `.sw-crud-container`, `.sw-crud-head`, `.sw-crud-title`, `.sw-crud-actions`.
- Layout base: `.sw-app`, `.sw-body`, `.sw-main`, `.sw-footer`, `.sw-footer-links` (topbar y sidebar tienen su propia familia, ver abajo).
- Filtros y listados: `.sw-list-card`, `.sw-list-toolbar`, `.sw-list-search`, `.sw-list-filter-row`, `.sw-list-filter-main`, `.sw-list-filter-control` (`.sw-list-filter-control-sm` para selectores cortos), `.sw-list-filter-actions`, `.sw-list-meta`, `.sw-list-count`, `.sw-list-context`.
- Tablas: `.sw-table-wrap`, `.sw-list-table-wrap`, `.sw-table`, `.sw-table-min-md`, `.sw-table-min-lg`, `.sw-table-min-xl`, `.sw-table-actions`, `.sw-bico` (+ `:disabled` para acciones no disponibles), `.sw-row-menu`/`.sw-row-menu-dropdown`/`.sw-row-menu-item` (menu "..." de acciones secundarias por fila), `.sw-bico-hide-compact`/`.sw-row-menu-compact-only` (mueven una acción de fila al menú "..." por debajo de 1200px; usar con 3+ acciones; en `.sw-row-menu` crea un menú que solo existe por debajo de 1200px), `.sw-money`.
- Formularios: `.sw-field`, `.sw-label`, `.sw-input`, `.sw-select`, `.sw-textarea`, `.sw-error`, `.sw-required`, `.sw-hint`, `.sw-hint-error`, `.sw-form-actions`.
- Tabs: `.sw-panel-tabs`, `.sw-panel-card`, `.sw-panel-tab-pane`.
- Resúmenes funcionales: `.sw-section-block`, `.sw-section-title`, `.sw-data-grid`, `.sw-data-item`, `.sw-data-label`, `.sw-data-value`, `.sw-total-panel`, `.sw-total-label`, `.sw-total-value`, `.sw-total-copy`.
- Avatares e imágenes: `.sw-user-avatar` (+ modificador `.sw-avatar-lg`), `.sw-report-logo`, `.sw-logo-preview` (combinar con `.sw-card` para fondo/borde), `.sw-table-thumb` (miniatura 40px en filas de tabla, ej. imagen de producto).
- Dashboard: `.sw-metric`, `.sw-metrics-grid` (solo si el usuario pide métricas), `.sw-quicklink-grid`, `.sw-quicklink`, `.sw-quicklink-icon`, `.sw-quicklink-title`, `.sw-quicklink-subtitle`, `.sw-chart-card`, `.sw-chart-wrap`, `.sw-chart-empty`.

## Utilidades

> ⚠️ **Las utilidades de Bootstrap NO existen en este sistema.** `row`, `col-*`,
> `mb-4`, `d-flex`, `justify-content-between`, `h-100`, `text-right`,
> `font-weight-bold`, `text-muted`, `w-100`… no tienen CSS y fallan en silencio:
> el layout se rompe sin ningún error visible. Usar siempre la versión `sw-`.

**Grid** — `.sw-row` + `.sw-col-4` / `.sw-col-6` / `.sw-col-8` / `.sw-col-12`.
Los `sw-col-*` son 100% en móvil y toman su fracción desde 768px. El gutter sale
del padding de la columna, así que un `sw-col-*` **siempre** va dentro de un
`.sw-row`. Para campos cortos (números, códigos, porcentajes) que en celular
no necesitan todo el ancho, agregar `.sw-col-xs-1` … `.sw-col-xs-12`, que rigen
desde 0px: `sw-col-xs-6 sw-col-4` da 2 por fila en celular y 3 desde 768px.

**Display / flex** — `.sw-d-flex`, `.sw-d-block`, `.sw-d-none`, `.sw-flex-wrap`,
`.sw-flex-column`, `.sw-flex-row`, `.sw-align-items-start|end|center|stretch`,
`.sw-align-self-start|end|center`,
`.sw-justify-content-start|end|center|between|around`.

**Responsive** (para cabeceras que se apilan en móvil):
`.sw-flex-sm-row` (≥576), `.sw-flex-md-row` (≥768), `.sw-flex-lg-row` (≥992),
`.sw-align-items-sm-center`, `.sw-align-items-md-center|start`,
`.sw-align-items-lg-center|start`, `.sw-justify-content-lg-end`.

Patrón típico de cabecera de card:

```html
<div class="sw-d-flex sw-flex-column sw-flex-md-row sw-justify-content-between sw-align-items-md-center sw-mb-3">
    <div>
        <h2 class="sw-card-title sw-mb-1">Título</h2>
        <p class="sw-card-body sw-mb-0">Subtítulo.</p>
    </div>
    <span class="sw-badge sw-badge-success">Activo</span>
</div>
```

**Espaciado** — escala `0=0, 1=.25rem, 2=.5rem, 3=1rem, 4=1.5rem, 5=3rem`:
`.sw-mt-0..4`, `.sw-mb-0..4`, `.sw-mr-0|2|3`, `.sw-ml-0|2`, `.sw-pt-3|4`,
`.sw-pb-0|3|4`, `.sw-px-4`, `.sw-py-2|3|5`, `.sw-p-0`.
Variantes responsive para resetear al subir de breakpoint:
`.sw-mb-sm-0`, `.sw-mt-sm-0`, `.sw-ml-sm-2`, `.sw-mr-sm-2`, `.sw-mb-md-0`,
`.sw-mt-md-0`, `.sw-mb-lg-0`, `.sw-mt-lg-0`, `.sw-mr-lg-3`, `.sw-mb-xl-0`,
`.sw-mt-xl-0`.

**Texto y otros** — `.sw-text-left|center|right`, `.sw-text-muted`,
`.sw-text-3xs|2xs|xs|sm|base|md|lg|lg-plus|xl|2xl|3xl`,
`.sw-text-success`, `.sw-font-bold`, `.sw-nowrap`, `.sw-h-100`, `.sw-border-0`,
`.sw-border-bottom`.

`text-left`, `text-center` y `text-right` (sin prefijo) están aliasados a sus
equivalentes `sw-` por compatibilidad con vistas heredadas. Es la **única**
excepción; en código nuevo usar el prefijo.

Si falta una utilidad, agregarla a `src/base/utilities.css` y reconstruir.
No inventar clases sueltas por vista.

```css
.sw-app {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.sw-body {
    display: flex;
    flex: 1;
    overflow: hidden;
}

.sw-main {
    flex: 1;
    overflow-y: auto;
    padding: 24px 28px;
    background: var(--b2);
}

/* ── Footer — tercer hijo flex de .sw-app, siempre pegado abajo ── */
.sw-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 10px 20px;
    border-top: var(--brd);
    background: var(--b1);
    color: var(--bc2);
    font-size: 12px;
}

.sw-footer a {
    color: var(--p);
}

.sw-footer a:hover {
    text-decoration: underline;
}

.sw-footer-links {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
}

.sw-page-header {
    margin-bottom: 24px;
}

.sw-page-title {
    color: var(--bc);
    font-size: 28px;
    font-weight: 700;
    line-height: 1.15;
    margin-bottom: 6px;
}

.sw-page-subtitle {
    color: var(--bc2);
    font-size: 15px;
    line-height: 1.5;
}

.sw-topbar {
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    background: var(--b1);
    border-bottom: var(--brd);
    position: sticky;
    top: 0;
    z-index: 40;
    transition: background 0.25s;
    box-shadow: var(--sh-sm);
}

.sw-topbar-brand,
.sw-topbar-actions {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.sw-topbar-actions {
    gap: 10px;
}

.sw-topbar-logo {
    height: 36px;
    width: auto;
    object-fit: contain;
    flex-shrink: 0;
}

.sw-topbar-tenant {
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
}

.sw-topbar-company {
    display: block;
    max-width: min(42vw, 480px);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--bc);
    font-size: 16px;
    font-weight: 700;
    line-height: 1.2;
}

.sw-topbar-period {
    display: block;
    max-width: min(42vw, 480px);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--bc2);
    font-size: 13px;
    font-weight: 500;
    line-height: 1.25;
}

.sw-topbar-period i {
    color: var(--p);
    font-size: 12px;
    margin-right: 4px;
}

.sw-user-menu {
    position: relative;
}

.sw-user-trigger {
    display: flex;
    align-items: center;
    gap: 9px;
    height: 44px;
    max-width: 290px;
    padding: 4px 10px 4px 6px;
    background: transparent;
    border: 1px solid transparent;
    border-radius: var(--r);
    color: var(--bc);
    cursor: pointer;
    transition:
        background 0.15s,
        border-color 0.15s;
}

.sw-user-trigger:hover,
.sw-user-trigger[aria-expanded="true"] {
    background: var(--b1);
    border-color: color-mix(in srgb, var(--s) 34%, transparent);
}

.sw-user-trigger:focus-visible,
.sw-user-action:focus-visible {
    outline: 2px solid var(--p);
    outline-offset: 1px;
}

.sw-user-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: color-mix(in srgb, var(--p) 12%, var(--b1));
    color: var(--p);
    font-size: 15px;
}

.sw-user-avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.sw-avatar-lg {
    width: 84px;
    height: 84px;
    font-size: 34px;
}

.sw-user-avatar-fallback {
    display: none;
}

.sw-user-avatar-photo.is-fallback .sw-user-avatar-img {
    display: none;
}

.sw-user-avatar-photo.is-fallback .sw-user-avatar-fallback {
    display: inline-block;
}

.sw-user-name {
    max-width: 190px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 14px;
    font-weight: 500;
}

.sw-user-arrow {
    color: var(--bc2);
    font-size: 12px;
    transition: transform 0.15s;
}

.sw-user-trigger[aria-expanded="true"] .sw-user-arrow {
    transform: rotate(180deg);
}

.sw-user-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    z-index: 80;
    min-width: 220px;
    padding: 8px;
    background: var(--b1);
    border: var(--brd);
    border-radius: var(--r);
    box-shadow: var(--sh-md);
}

.sw-user-dropdown.open {
    display: block;
}

.sw-user-role {
    display: flex;
    flex-direction: column;
    gap: 2px;
    margin-bottom: 6px;
    padding: 6px 8px 10px;
    border-bottom: var(--brd);
}

.sw-user-role-label {
    color: var(--bc2);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.sw-user-role-value {
    color: var(--bc);
    font-size: 14px;
    font-weight: 600;
}

.sw-user-dropdown form {
    margin: 0;
}

.sw-user-action {
    display: flex;
    align-items: center;
    gap: 9px;
    width: 100%;
    padding: 10px 8px;
    background: transparent;
    border: 0;
    border-radius: var(--r);
    color: var(--er);
    font-family: inherit;
    font-size: 14px;
    font-weight: 500;
    text-align: left;
    cursor: pointer;
}

.sw-user-action:hover {
    background: color-mix(in srgb, var(--er) 8%, transparent);
}

.sw-user-action-neutral {
    color: var(--bc);
}

.sw-user-action-neutral:hover {
    background: var(--b2);
}

.sw-btn.sw-sidebar-toggle,
.sw-sidebar-backdrop {
    display: none;
}

.sw-sidebar {
    width: 245px;
    background: var(--sb);
    display: flex;
    flex-direction: column;
    padding: 16px 12px;
    flex-shrink: 0;
    overflow-y: auto;
    transition: background 0.25s;
}

.sw-sb-brand {
    padding: 4px 0.75rem 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.07);
    margin-bottom: 8px;
}

.sw-sb-brand-name {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.sw-sb-brand-sub {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.42);
    margin-top: 2px;
}

.sw-sb-group {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(255, 255, 255, 0.35);
    padding: 0 0.75rem;
    margin: 18px 0 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sw-sb-group::after {
    content: "";
    flex: 1;
    height: 1px;
    background: rgba(255, 255, 255, 0.07);
}

.sw-sb-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.65rem 0.85rem;
    border-radius: var(--r);
    color: rgba(255, 255, 255, 0.82);
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    transition:
        background 0.15s,
        color 0.15s,
        border-radius 0.15s;
}

.sw-sb-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.sw-sb-link:focus-visible,
.sw-sb-sub-link:focus-visible {
    outline: 2px solid var(--s);
    outline-offset: -2px;
    color: #fff;
}

.sw-sb-link.active {
    background: rgba(255, 255, 255, 0.14);
    color: #fff;
    border-left: 3px solid var(--s);
    padding-left: calc(0.85rem - 3px);
}

.sw-sb-link i {
    width: 18px;
    text-align: center;
    font-size: 14px;
}

.sw-sb-link .sw-arrow {
    margin-left: auto;
    font-size: 12px;
    transition: transform 0.2s;
}

.sw-sb-link.open .sw-arrow {
    transform: rotate(90deg);
}

.sw-sidebar > .sw-sb-link {
    margin-bottom: 0.15rem;
}

.sw-sb-toggle,
.sw-sb-action {
    width: 100%;
    border: 0;
    font-family: inherit;
    line-height: inherit;
    text-align: left;
    background: transparent;
}

.sw-sidebar > .sw-sb-link.open {
    margin-bottom: 0;
    padding-left: calc(0.85rem - 3px);
    border-left: 3px solid var(--s);
    border-radius: var(--r) var(--r) 0 0;
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
}

.sw-sb-sub {
    overflow: hidden;
    max-height: 0;
    margin: 0;
    padding: 0 0.5rem 0 0.7rem;
    border-left: 3px solid transparent;
    border-radius: 0 0 0.75rem 0.75rem;
    background: transparent;
    transition: max-height 0.25s ease;
}

.sw-sb-sub.open {
    max-height: 480px;
    margin: 0 0 0.55rem;
    padding: 0.5rem 0.5rem 0.5rem 0.7rem;
    border-left-color: var(--s);
    background: rgba(0, 0, 0, 0.14);
}

.sw-sb-sub-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.62rem 0.7rem;
    border-radius: 0.45rem;
    color: rgba(255, 255, 255, 0.78);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition:
        background 0.15s,
        color 0.15s;
}

.sw-sb-sub-link i {
    width: 15px;
    text-align: center;
    font-size: 13px;
    opacity: 0.75;
}

.sw-sb-sub-link:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.08);
}

.sw-sb-sub-link.active {
    color: #fff;
    font-weight: 700;
    background: rgba(255, 255, 255, 0.12);
}

.sw-sb-form {
    margin: 0;
}

.sw-sb-action:hover {
    background: color-mix(in srgb, var(--er) 18%, transparent);
}

@media (max-width: 767.98px) {
    body.sw-sidebar-open {
        overflow: hidden;
    }

    .sw-topbar {
        padding: 0 14px;
        gap: 10px;
    }

    .sw-topbar-brand {
        gap: 10px;
        overflow: hidden;
    }

    .sw-topbar-company {
        font-size: 14px;
        max-width: 120px;
    }

    .sw-topbar-period {
        font-size: 12px;
        max-width: 120px;
    }

    .sw-btn.sw-sidebar-toggle {
        display: inline-flex;
    }

    .sw-topbar-actions > .sw-btn,
    .sw-user-name,
    .sw-user-arrow {
        display: none;
    }

    .sw-user-trigger {
        padding-right: 6px;
    }

    .sw-sidebar {
        position: fixed;
        top: 68px;
        bottom: 0;
        left: 0;
        z-index: 60;
        visibility: hidden;
        transform: translateX(-100%);
        transition:
            background 0.25s,
            transform 0.2s ease,
            visibility 0.2s ease;
    }

    body.sw-sidebar-open .sw-sidebar {
        visibility: visible;
        transform: translateX(0);
    }

    .sw-sidebar-backdrop {
        position: fixed;
        inset: 68px 0 0;
        z-index: 50;
        display: block;
        border: 0;
        background: rgba(0, 0, 0, 0.38);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }

    body.sw-sidebar-open .sw-sidebar-backdrop {
        opacity: 1;
        pointer-events: auto;
    }

    .sw-main {
        padding: 16px 14px;
    }
}

.sw-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.6rem 1.25rem;
    font-family: "Inter", sans-serif;
    font-size: 15px;
    font-weight: 500;
    border: none;
    border-radius: var(--rl);
    cursor: pointer;
    transition:
        opacity 0.15s,
        box-shadow 0.15s;
    color: var(--btn-text);
    line-height: 1.4;
}

.sw-btn:hover {
    opacity: 0.9;
    box-shadow: var(--sh-md);
}

.sw-btn:focus {
    outline: 2px solid var(--a);
    outline-offset: 2px;
}

.sw-btn:disabled {
    opacity: 0.38;
    cursor: not-allowed;
    box-shadow: none;
}

.sw-btn-primary {
    background: var(--p);
}

.sw-btn-accent {
    background: var(--a);
}

.sw-btn-error {
    background: var(--er);
}

.sw-btn-success {
    background: var(--su);
}

.sw-btn-ghost {
    background: transparent;
    color: var(--bc);
    border: var(--brd);
}

.sw-btn-ghost:hover {
    background: var(--b2);
    opacity: 1;
    box-shadow: none;
}

.sw-btn-outline {
    background: transparent;
    color: var(--p);
    border: 1.5px solid var(--p);
}

.sw-btn-outline:hover {
    background: color-mix(in srgb, var(--p) 6%, transparent);
    opacity: 1;
}

.sw-btn-light {
    background: var(--b1);
    color: var(--p);
    border: 1.5px solid var(--p);
}

.sw-btn-light:hover {
    background: color-mix(in srgb, var(--p) 6%, var(--b1));
    opacity: 1;
}

.sw-btn-sm {
    padding: 0.45rem 1rem;
    font-size: 14px;
    border-radius: var(--r);
}

.sw-btn-lg {
    padding: 0.75rem 1.6rem;
    font-size: 17px;
}

.sw-bico {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 31px;
    border: var(--brd-s);
    border-radius: var(--r);
    background: var(--b1);
    cursor: pointer;
    font-size: 13px;
    transition:
        background 0.15s,
        border-color 0.15s,
        color 0.15s,
        opacity 0.15s;
    color: var(--bc);
}

.sw-bico:hover {
    background: color-mix(in srgb, var(--p) 7%, var(--b1));
    border-color: color-mix(in srgb, var(--p) 34%, transparent);
    color: var(--p);
    opacity: 1;
}

.sw-bico:disabled {
    background: var(--b1);
    color: var(--bc2);
    opacity: 0.38;
    cursor: not-allowed;
    box-shadow: none;
}

.sw-bico:disabled:hover {
    background: var(--b1);
    color: var(--bc2);
}

.sw-bico-edit {
    background: var(--b1);
    border-color: color-mix(in srgb, var(--p) 30%, transparent);
    color: var(--bc);
}

.sw-bico-copy {
    background: var(--p);
    border-color: var(--p);
    color: var(--btn-text);
}

.sw-bico-delete {
    background: var(--er);
    border-color: var(--er);
    color: var(--erc);
}

.sw-bico-success {
    background: color-mix(in srgb, var(--su) 14%, var(--b1));
    border-color: color-mix(in srgb, var(--su) 40%, transparent);
    color: color-mix(in srgb, var(--su) 62%, #000);
}

.sw-bico-info {
    background: color-mix(in srgb, var(--in) 12%, var(--b1));
    border-color: color-mix(in srgb, var(--in) 38%, transparent);
    color: color-mix(in srgb, var(--in) 62%, #000);
}

.sw-bico-warning {
    background: color-mix(in srgb, var(--wa) 14%, var(--b1));
    border-color: color-mix(in srgb, var(--wa) 42%, transparent);
    color: color-mix(in srgb, var(--wa) 54%, #000);
}

.sw-bico-transfer {
    background: color-mix(in srgb, var(--wa) 14%, var(--b1));
    border-color: color-mix(in srgb, var(--wa) 42%, transparent);
    color: color-mix(in srgb, var(--wa) 54%, #000);
}

[data-theme="dark"] .sw-bico,
[data-theme="dark"] .sw-bico-edit {
    background: var(--b1);
    border-color: color-mix(in srgb, var(--s) 32%, transparent);
    color: var(--bc);
}

[data-theme="dark"] .sw-bico:hover {
    background: color-mix(in srgb, var(--s) 10%, var(--b1));
    color: var(--p);
}

[data-theme="dark"] .sw-bico-delete {
    background: var(--er);
    border-color: var(--er);
    color: var(--erc);
}

[data-theme="dark"] .sw-bico-success {
    background: color-mix(in srgb, var(--su) 16%, transparent);
    color: var(--su);
}

[data-theme="dark"] .sw-bico-info {
    background: color-mix(in srgb, var(--in) 16%, transparent);
    color: var(--in);
}

[data-theme="dark"] .sw-bico-warning,
[data-theme="dark"] .sw-bico-transfer {
    background: color-mix(in srgb, var(--wa) 16%, transparent);
    color: var(--wa);
}

.sw-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0.28rem 0.8rem;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    line-height: 1;
}

.sw-badge-primary {
    background: color-mix(in srgb, var(--p) 12%, transparent);
    color: var(--p);
}

.sw-badge-secondary {
    background: color-mix(in srgb, var(--s) 18%, transparent);
    color: color-mix(in srgb, var(--s) 75%, #000);
}

.sw-badge-accent {
    background: color-mix(in srgb, var(--a) 15%, transparent);
    color: color-mix(in srgb, var(--a) 78%, #000);
}

.sw-badge-success {
    background: color-mix(in srgb, var(--su) 14%, transparent);
    color: color-mix(in srgb, var(--su) 62%, #000);
}

.sw-badge-warning {
    background: color-mix(in srgb, var(--wa) 16%, transparent);
    color: color-mix(in srgb, var(--wa) 52%, #000);
}

.sw-badge-error {
    background: color-mix(in srgb, var(--er) 11%, transparent);
    color: var(--er);
}

.sw-badge-info {
    background: color-mix(in srgb, var(--in) 11%, transparent);
    color: color-mix(in srgb, var(--in) 68%, #000);
}

[data-theme="dark"] .sw-badge-success {
    color: var(--su);
}

[data-theme="dark"] .sw-badge-warning {
    color: var(--wa);
}

[data-theme="dark"] .sw-badge-accent {
    color: var(--a);
}

[data-theme="dark"] .sw-badge-secondary {
    color: var(--s);
}

[data-theme="dark"] .sw-badge-info {
    color: var(--in);
}

.sw-alert {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 0.9rem 1.1rem;
    border-radius: var(--r);
    font-size: 15px;
    line-height: 1.55;
}

.sw-alert-info {
    background: color-mix(in srgb, var(--in) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--in) 22%, transparent);
    color: color-mix(in srgb, var(--in) 68%, #000);
}

.sw-alert-success {
    background: color-mix(in srgb, var(--su) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--su) 22%, transparent);
    color: color-mix(in srgb, var(--su) 62%, #000);
}

.sw-alert-warning {
    background: color-mix(in srgb, var(--wa) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--wa) 26%, transparent);
    color: color-mix(in srgb, var(--wa) 50%, #000);
}

.sw-alert-error {
    background: color-mix(in srgb, var(--er) 8%, transparent);
    border: 1px solid color-mix(in srgb, var(--er) 18%, transparent);
    color: var(--er);
}

[data-theme="dark"] .sw-alert-info {
    color: var(--in);
}

[data-theme="dark"] .sw-alert-success {
    color: var(--su);
}

[data-theme="dark"] .sw-alert-warning {
    color: var(--wa);
}

.sw-field {
    display: flex;
    flex-direction: column;
    margin-top: 3px;
    margin-bottom: 3px;
}

.sw-label {
    font-size: 14px;
    font-weight: 500;
    color: var(--bc2);
}

.sw-input {
    width: 100%;
    padding: 0.65rem 1rem;
    font-family: "Inter", sans-serif;
    font-size: 15px;
    color: var(--bc);
    background: var(--b1);
    border: 1.5px solid color-mix(in srgb, var(--s) 42%, transparent);
    border-radius: var(--r);
    outline: none;
    transition:
        border-color 0.15s,
        box-shadow 0.15s;
}

.sw-input:focus {
    border-color: var(--p);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--p) 12%, transparent);
}

.sw-input.sw-error,
.sw-select.sw-error,
.sw-textarea.sw-error {
    border-color: var(--er);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--er) 10%, transparent);
}

.sw-select {
    width: 100%;
    padding: 0.65rem 1rem;
    font-family: "Inter", sans-serif;
    font-size: 15px;
    color: var(--bc);
    background: var(--b1);
    border: 1.5px solid color-mix(in srgb, var(--s) 42%, transparent);
    border-radius: var(--r);
    outline: none;
    cursor: pointer;
}

.sw-select:focus {
    border-color: var(--p);
}

.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--single {
    display: flex;
    align-items: center;
    min-height: 43px;
    border: 1.5px solid color-mix(in srgb, var(--s) 42%, transparent);
    border-radius: var(--r);
    background: var(--b1);
    color: var(--bc);
    outline: none;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    width: 100%;
    padding-left: 1rem;
    padding-right: 2.25rem;
    color: var(--bc);
    font-family: "Inter", sans-serif;
    font-size: 15px;
    line-height: 1.4;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 6px;
    right: 8px;
}

.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: var(--p);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--p) 12%, transparent);
}

.select2-dropdown {
    border: var(--brd);
    border-radius: var(--r);
    background: var(--b1);
    color: var(--bc);
}

.select2-search--dropdown .select2-search__field {
    border: 1.5px solid color-mix(in srgb, var(--s) 42%, transparent);
    border-radius: var(--r);
    color: var(--bc);
    outline: none;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: var(--p);
    color: var(--btn-text);
}

.sw-textarea {
    width: 100%;
    padding: 0.65rem 1rem;
    font-family: "Inter", sans-serif;
    font-size: 15px;
    color: var(--bc);
    background: var(--b1);
    border: 1.5px solid color-mix(in srgb, var(--s) 42%, transparent);
    border-radius: var(--r);
    outline: none;
    resize: vertical;
}

.sw-textarea:focus {
    border-color: var(--p);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--p) 12%, transparent);
}

.sw-hint {
    font-size: 13px;
    color: var(--bc2);
}

.sw-hint-error {
    font-size: 13px;
    color: var(--er);
}

.sw-required {
    color: var(--er);
}

.sw-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 1.5rem;
}

.sw-input-icon-wrap {
    position: relative;
}

.sw-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--bc2);
    font-size: 14px;
    pointer-events: none;
}

.sw-input-has-icon {
    padding-left: 2.5rem;
}

.sw-ss-wrap {
    position: relative;
    width: 100%;
}

.sw-ss-trigger {
    width: 100%;
    padding: 0.65rem 1rem;
    font-family: "Inter", sans-serif;
    font-size: 15px;
    color: var(--bc);
    background: var(--b1);
    border: 1.5px solid color-mix(in srgb, var(--s) 42%, transparent);
    border-radius: var(--r);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    transition: border-color 0.15s;
}

.sw-ss-trigger.open {
    border-color: var(--p);
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--p) 12%, transparent);
}

.sw-ss-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--b1);
    border: 1.5px solid var(--p);
    border-top: none;
    border-bottom-left-radius: var(--r);
    border-bottom-right-radius: var(--r);
    z-index: 100;
    box-shadow: var(--sh-md);
    display: none;
}

.sw-ss-dropdown.open {
    display: block;
}

.sw-ss-search {
    width: 100%;
    padding: 0.55rem 1rem 0.55rem 2.2rem;
    font-family: "Inter", sans-serif;
    font-size: 15px;
    color: var(--bc);
    background: var(--b2);
    border: var(--brd);
    border-radius: 0.35rem;
    outline: none;
    display: block;
    box-sizing: border-box;
}

.sw-ss-opt {
    padding: 0.6rem 1.1rem;
    font-size: 15px;
    cursor: pointer;
    color: var(--bc);
    display: flex;
    align-items: center;
    gap: 9px;
    transition: background 0.12s;
}

.sw-ss-opt:hover {
    background: color-mix(in srgb, var(--p) 6%, transparent);
}

.sw-ss-opt.selected {
    background: color-mix(in srgb, var(--p) 10%, transparent);
    color: var(--p);
    font-weight: 600;
}

.sw-ss-empty {
    padding: 0.8rem 1.1rem;
    font-size: 15px;
    color: var(--bc2);
    text-align: center;
}

.sw-toggle {
    width: 46px;
    height: 26px;
    background: color-mix(in srgb, var(--s) 32%, transparent);
    border-radius: 25px;
    position: relative;
    cursor: pointer;
    border: none;
    flex-shrink: 0;
    transition: background 0.2s;
}

.sw-toggle.on {
    background: var(--p);
}

.sw-toggle.on-accent {
    background: var(--a);
}

.sw-toggle::after {
    content: "";
    position: absolute;
    top: 3px;
    left: 3px;
    width: 20px;
    height: 20px;
    background: #fff;
    border-radius: 50%;
    transition: left 0.18s;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.18);
}

.sw-toggle.on::after,
.sw-toggle.on-accent::after {
    left: 23px;
}

.sw-checkbox-input {
    appearance: none;
    width: 19px;
    height: 19px;
    border: 2px solid color-mix(in srgb, var(--s) 55%, transparent);
    border-radius: 4px;
    background: var(--b1);
    cursor: pointer;
    flex-shrink: 0;
}

.sw-checkbox-input:checked {
    background: var(--p);
    border-color: var(--p);
    box-shadow: inset 0 0 0 3px var(--b1);
}

.sw-radio {
    width: 19px;
    height: 19px;
    border: 2px solid color-mix(in srgb, var(--s) 55%, transparent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--b1);
    flex-shrink: 0;
}

.sw-radio.on {
    border-color: var(--p);
}

.sw-radio.on::after {
    content: "";
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--p);
}

.sw-table-wrap {
    border: var(--brd);
    border-radius: var(--rl);
    overflow: hidden;
    background: var(--b1);
    box-shadow: var(--sh-sm);
}

.sw-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    font-variant-numeric: tabular-nums;
}

.sw-table thead th {
    background: var(--th-bg);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--th-color);
    padding: 0.62rem 0.8125rem;
    border-bottom: 2px solid color-mix(in srgb, var(--p) 20%, transparent);
    text-align: left;
}

.sw-table tbody td {
    padding: 0.5rem 0.8125rem;
    border-bottom: var(--brd);
    color: var(--bc);
    vertical-align: middle;
    background: var(--b1);
    line-height: 1.35;
}

/* Hasta 1280px la tabla normal se ajusta sola: misma densidad que
   .sw-table-compact. Por encima conserva letra y relleno normales.
   .sw-table-compact queda densa en todos los anchos (reportes con muchas
   columnas). */
@media (max-width: 1280.98px) {
    .sw-table thead th {
        padding: 0.55rem 0.65rem;
        font-size: 11px;
    }

    .sw-table tbody td {
        padding: 0.45rem 0.65rem;
        font-size: 13px;
    }
}

.sw-table tbody tr:last-child td {
    border-bottom: none;
}

.sw-table tbody tr:hover td {
    background: var(--b2);
}

.sw-empty-icon {
    color: var(--bc2);
    font-size: 2rem;
}

.sw-empty-title {
    color: var(--bc);
    font-weight: 700;
}

.sw-empty-copy {
    color: var(--bc2);
}

.sw-crud-container {
    padding-top: 30px;
    padding-bottom: 22px;
}

.sw-crud-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px;
}

.sw-crud-title {
    min-width: 0;
}

.sw-crud-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 8px;
}

.sw-crud-actions .sw-btn,
.sw-list-search-row .sw-btn,
.sw-list-filter-actions .sw-btn {
    flex: 0 0 auto;
    white-space: nowrap;
}

.sw-card.sw-list-card {
    overflow: visible;
    padding: 22px;
    box-shadow: 0 8px 24px color-mix(in srgb, var(--p) 8%, transparent);
}

.sw-list-toolbar {
    display: flex;
    align-items: stretch;
    flex-direction: column;
    gap: 13px;
    padding: 0 0 16px;
    border-bottom: 0;
    background: transparent;
}

.sw-list-search {
    display: flex;
    align-items: stretch;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
    width: 100%;
}

.sw-list-search-label {
    color: var(--bc2);
    font-size: 14px;
    font-weight: 500;
    line-height: 1.2;
    margin: 0;
}

.sw-list-search-row,
.sw-list-filter-row {
    display: flex;
    align-items: flex-end;
    gap: 30px;
    width: 100%;
}

.sw-list-search-row .sw-input-icon-wrap,
.sw-list-filter-main {
    flex: 1 1 auto;
    min-width: 0;
}

.sw-list-filter-control {
    flex: 0 1 340px;
    min-width: 220px;
}

.sw-list-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 0 0 auto;
}

.sw-list-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    min-height: 22px;
}

.sw-list-count,
.sw-list-context {
    color: color-mix(in srgb, var(--bc2) 88%, var(--p));
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
}

.sw-list-table-wrap {
    border: var(--brd-s);
    border-radius: 1rem;
    box-shadow: none;
    overflow: auto;
    max-height: 70vh;
}

/* Cabecera fija mientras se scrollea vertical dentro del wrap acotado */
.sw-list-table-wrap thead th {
    position: sticky;
    top: 0;
    z-index: 2;
}

/* Columna marcada fija a la derecha en todos los anchos: en movil es lo que
   mantiene alcanzables las acciones mientras se hace scroll horizontal.
   Agregar .sw-table-sticky-col al th y td de la columna elegida. */
.sw-list-table-wrap tr > .sw-table-sticky-col {
    position: sticky;
    right: 0;
    z-index: 1;
    /* Ancho justo para su contenido: si la tabla reparte ancho sobrante, la
       columna fija no debe crecer y tapar las demas en pantallas angostas. */
    width: 1%;
    white-space: nowrap;
}

.sw-list-table-wrap thead tr > .sw-table-sticky-col {
    top: 0;
    z-index: 3;
}

.sw-list-table-wrap.is-table-sticky-displaced tr > .sw-table-sticky-col::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 2px;
    background: var(--sw-table-sticky-border);
    pointer-events: none;
}

.sw-table-min-md {
    min-width: 760px;
}

.sw-table-min-lg {
    min-width: 920px;
}

.sw-table-min-xl {
    min-width: 1080px;
}

/* Las utilidades de alineacion pierden contra `.sw-table thead th`
   (text-align: left, mas especifica): aqui se refuerzan para th y td, para
   que el encabezado pueda alinearse igual que su columna. */
.sw-table th.sw-text-center,
.sw-table td.sw-text-center,
.sw-table th.text-center,
.sw-table td.text-center {
    text-align: center;
}

.sw-table th.sw-text-right,
.sw-table td.sw-text-right,
.sw-table th.text-right,
.sw-table td.text-right {
    text-align: right;
}

.sw-table-main-cell,
.sw-text-stack {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.sw-table-expand-wrap { display: flex; align-items: center; gap: calc(var(--sw-spacing-sm) + 0.3rem); }
.sw-table-select-all-wrap { display: inline-flex; align-items: center; gap: calc(var(--sw-spacing-sm) + 0.3rem); }
.sw-table-expand { width: 28px; height: 28px; padding: 0; border: 0; border-radius: var(--sw-radius-sm); background: transparent; color: var(--p); cursor: pointer; }
.sw-table-expand[aria-expanded="true"] i { transform: rotate(90deg); }
.sw-table-detail-row[hidden] { display: none; }
.sw-table-detail-row > td { padding: 0; background: var(--b2) !important; }
.sw-table-detail { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: var(--sw-spacing-md); padding: var(--sw-spacing-md); }

.sw-table-title {
    display: block;
    max-width: 360px;
    overflow: hidden;
    color: var(--bc);
    font-size: 15px;
    font-weight: 700;
    line-height: 1.25;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sw-table-subtitle,
.sw-text-stack small {
    display: block;
    max-width: 300px;
    overflow: hidden;
    color: color-mix(in srgb, var(--bc2) 82%, var(--p));
    font-size: 12px;
    line-height: 1.35;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sw-text-stack a {
    display: block;
    max-width: 300px;
    overflow: hidden;
    color: var(--p);
    font-size: 14px;
    font-weight: 500;
    line-height: 1.35;
    text-decoration: none;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sw-text-stack a:hover {
    color: var(--s);
    text-decoration: none;
}

.sw-text-ellipsis {
    display: block;
    max-width: 340px;
    overflow: hidden;
    color: var(--bc);
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sw-table-actions {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-wrap: nowrap;
    gap: 6px;
}

/* Menu de acciones secundarias por fila (icono "..." + panel flotante) */
.sw-row-menu {
    position: relative;
    display: inline-flex;
}

.sw-row-menu-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 6px);
    z-index: 80;
    min-width: 190px;
    padding: 6px;
    background: var(--b1);
    border: var(--brd);
    border-radius: var(--r);
    box-shadow: var(--sh-md);
    text-align: left;
}

.sw-row-menu-dropdown.open {
    display: block;
}

.sw-row-menu-item {
    display: flex;
    align-items: center;
    gap: 9px;
    width: 100%;
    padding: 8px 10px;
    background: transparent;
    border: 0;
    border-radius: var(--r);
    color: var(--bc);
    font-family: inherit;
    font-size: 14px;
    font-weight: 500;
    text-align: left;
    text-decoration: none;
    cursor: pointer;
}

.sw-row-menu-item:hover {
    background: var(--b2);
    color: var(--bc);
    text-decoration: none;
}

.sw-row-menu-item i {
    width: 16px;
    text-align: center;
    color: var(--bc2);
}

.sw-table .sw-badge {
    justify-content: center;
    min-width: 82px;
}

.sw-table .sw-badge i {
    font-size: 6px;
}

.sw-list-pagination {
    display: flex;
    justify-content: flex-end;
    padding: 16px 0 4px;
    border-top: 0;
}

.sw-list-pagination .pagination {
    gap: 4px;
    margin: 0;
}

.sw-list-pagination .page-link,
.sw-list-pagination .pagination li a,
.sw-list-pagination .pagination li span {
    border: var(--brd);
    border-radius: var(--r);
    color: var(--p);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    font-size: 12px;
    font-weight: 700;
    margin-left: 4px;
    padding: 0 0.75rem;
}

.sw-list-pagination .page-item.active .page-link,
.sw-list-pagination .pagination li.active a,
.sw-list-pagination .pagination li.active span {
    background: var(--p);
    border-color: var(--p);
    color: var(--btn-text);
}

.sw-empty-state {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--bc2);
    font-size: 14px;
    font-weight: 600;
}

.sw-empty-state i {
    color: var(--p);
    font-size: 18px;
}

/* Structured detail blocks */
.sw-section-block + .sw-section-block {
    margin-top: 1.25rem;
}

.sw-section-title {
    color: var(--p);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0;
    line-height: 1.25;
    margin: 0 0 0.85rem;
    text-transform: uppercase;
}

.sw-data-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px 22px;
}

.sw-data-item {
    display: grid;
    grid-template-columns: minmax(120px, 0.46fr) minmax(0, 1fr);
    gap: 10px;
    min-width: 0;
    padding: 0.38rem 0;
    border-bottom: 1px solid color-mix(in srgb, var(--s) 18%, transparent);
}

.sw-data-label {
    color: var(--bc2);
    font-size: 13px;
    font-weight: 600;
    line-height: 1.35;
}

.sw-data-value {
    min-width: 0;
    overflow-wrap: anywhere;
    color: var(--bc);
    font-size: 14px;
    font-weight: 500;
    line-height: 1.35;
}

.sw-money {
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}

.sw-total-panel {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 220px;
    height: 100%;
    padding: 1rem;
    border: var(--brd-s);
    border-radius: var(--r);
    background: color-mix(in srgb, var(--p) 4%, var(--b1));
}

.sw-total-label {
    color: var(--p);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0;
    line-height: 1.25;
    margin-bottom: 0.35rem;
    text-transform: uppercase;
}

.sw-total-value {
    color: var(--bc);
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
}

.sw-total-copy {
    color: var(--bc2);
    font-size: 13px;
    margin: 0.85rem 0 0;
}

@media (max-width: 991.98px) {
    .sw-crud-head {
        align-items: stretch;
        flex-direction: column;
    }

    .sw-crud-actions {
        justify-content: flex-start;
    }

    .sw-list-count {
        align-self: flex-start;
    }
}

@media (max-width: 575.98px) {
    .sw-crud-container {
        padding-top: 22px;
    }

    .sw-crud-actions,
    .sw-list-search-row,
    .sw-list-filter-row,
    .sw-list-filter-actions {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }

    .sw-list-search-row .sw-input-icon-wrap,
    .sw-list-filter-main,
    .sw-list-filter-control {
        flex-basis: auto;
        max-width: none;
        min-width: 0;
    }

    .sw-crud-actions .sw-btn,
    .sw-list-search-row .sw-btn,
    .sw-list-filter-actions .sw-btn {
        justify-content: center;
        width: 100%;
    }

    .sw-card.sw-list-card {
        padding: 14px;
    }

    .sw-table thead th,
    .sw-table tbody td {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    .sw-data-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .sw-data-item {
        grid-template-columns: minmax(0, 1fr);
        gap: 2px;
    }
}

/* ── Panel con tabs integradas (dos o mas secciones del mismo contexto) ── */
.sw-panel-tabs {
    background: transparent;
    border-radius: 1rem 1rem 0 0;
    gap: .35rem;
    margin-bottom: 0;
    overflow: visible;
    padding: 0;
}

.sw-panel-tabs .nav-item {
    flex: 0 0 auto;
}

.sw-panel-tabs .nav-link {
    align-items: center;
    background: var(--b2);
    border: 1px solid color-mix(in srgb, var(--s) 35%, transparent);
    border-bottom: 0;
    border-radius: .75rem .75rem 0 0;
    color: var(--bc2);
    display: flex;
    font-size: 13px;
    font-weight: 600;
    justify-content: center;
    margin-bottom: 0;
    min-height: 44px;
    padding: .7rem 1rem;
    transition: background .2s ease, color .2s ease;
}

.sw-panel-tabs .nav-link.active {
    background: var(--p);
    border-color: var(--p);
    color: var(--btn-text);
}

.sw-panel-tabs .nav-link:hover:not(.active) {
    color: var(--p);
}

.sw-panel-tabs .nav-link.active:hover {
    color: var(--btn-text);
}

.sw-panel-tab-pane:focus {
    outline: none;
}

.sw-card.sw-panel-card {
    border-top-left-radius: 0;
}

@media (max-width: 767.98px) {
    .sw-panel-tabs {
        gap: .5rem;
    }

    .sw-panel-tabs .nav-link {
        min-height: auto;
    }

    .sw-card.sw-panel-card {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
}

@media (max-width: 575.98px) {
    .sw-form-actions {
        align-items: stretch;
        flex-direction: column-reverse;
    }

    .sw-form-actions .sw-btn {
        justify-content: center;
        width: 100%;
    }
}

/* ── Reporte agrupado dentro de card (bloques por socio, por cuenta, etc.) ── */
.sw-report-logo {
    max-height: 48px;
    max-width: 72px;
    object-fit: contain;
}

.sw-logo-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 120px;
}

.sw-logo-preview img {
    max-width: 100%;
    max-height: 200px;
    object-fit: contain;
}

.sw-report-block + .sw-report-block {
    margin-top: 1.5rem;
}

.sw-report-heading {
    color: var(--p);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .04em;
    margin-bottom: .75rem;
    text-transform: uppercase;
}

.sw-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.sw-metric {
    background: var(--b1);
    border-radius: var(--r);
    padding: 1.1rem 1.2rem;
    border: var(--brd);
}

.sw-metric-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--s);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.sw-metric-value {
    font-size: 28px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

.sw-metric-sub {
    font-size: 14px;
    color: var(--bc2);
    margin-top: 3px;
}

/* ── Accesos rápidos (dashboard) ── */
.sw-quicklink-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.sw-quicklink {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border: var(--brd);
    border-radius: var(--r);
    color: var(--bc);
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
}

.sw-quicklink:hover {
    background: var(--b2);
    border-color: color-mix(in srgb, var(--p) 45%, transparent);
    color: var(--bc);
    text-decoration: none;
}

.sw-quicklink-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    margin-right: 0.85rem;
    border-radius: var(--r);
    background: color-mix(in srgb, var(--p) 12%, transparent);
    color: var(--p);
}

.sw-quicklink-title {
    display: block;
    font-weight: 700;
    line-height: 1.2;
    color: var(--bc);
}

.sw-quicklink-subtitle {
    display: block;
    margin-top: 0.15rem;
    font-size: 12px;
    color: var(--bc2);
}

/* ── Tarjetas de gráfico (dashboard) ── */
.sw-chart-card {
    min-height: 360px;
}

.sw-chart-wrap {
    position: relative;
    height: 270px;
}

.sw-chart-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    min-height: 270px;
    text-align: center;
    color: var(--bc2);
}

.sw-chart-empty i {
    margin-bottom: 0.75rem;
    font-size: 2rem;
    color: var(--bc2);
}

.sw-chart-empty strong {
    color: var(--bc);
}

.sw-card {
    background: var(--b1);
    border: var(--brd);
    border-radius: var(--rl);
    padding: 1.2rem 1.4rem;
    transition: box-shadow 0.18s;
}

.sw-card:hover {
    box-shadow: var(--sh-md);
}

.sw-card-title {
    font-size: 17px;
    font-weight: 700;
    color: var(--p);
    margin-bottom: 7px;
}

.sw-card-body {
    font-size: 15px;
    color: var(--bc2);
    line-height: 1.55;
}

.sw-card-footer {
    margin-top: 14px;
    padding-top: 12px;
    border-top: var(--brd);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sw-modal-backdrop {
    background: rgba(0, 0, 0, 0.42);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}

.sw-modal {
    background: var(--b1);
    border-radius: var(--rl);
    box-shadow: var(--sh-lg);
    padding: 2rem 2.2rem;
    width: 100%;
    max-width: 460px;
    border: var(--brd);
}

.sw-modal-sm { max-width: 360px; }
.sw-modal-lg { max-width: 760px; }

.sw-modal-title {
    font-size: 19px;
    font-weight: 700;
    color: var(--p);
    margin-bottom: 9px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 9px;
}

.sw-modal-close {
    background: transparent;
    border: none;
    color: var(--bc);
    cursor: pointer;
    font-size: 20px;
    padding: 4px 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.15s;
    flex-shrink: 0;
    line-height: 1;
}

.sw-modal-close:hover {
    color: var(--p);
}

.sw-modal-body {
    font-size: 15px;
    color: var(--bc2);
    line-height: 1.6;
    margin-bottom: 22px;
}

.sw-modal-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.sw-tabs {
    display: flex;
    border-bottom: var(--brd);
    margin-bottom: 18px;
}

.sw-tab {
    padding: 0.65rem 1.2rem;
    font-size: 15px;
    font-weight: 500;
    color: var(--bc2);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    transition: color 0.15s;
}

.sw-tab.active {
    color: var(--p);
    border-bottom-color: var(--p);
}

.sw-progress-track {
    height: 9px;
    background: color-mix(in srgb, var(--s) 18%, transparent);
    border-radius: 25px;
    overflow: hidden;
    margin-top: 6px;
}

.sw-progress-bar {
    height: 100%;
    border-radius: 25px;
}

.sw-pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: var(--r);
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: var(--brd);
    background: var(--b1);
    color: var(--bc);
    text-decoration: none;
}

.sw-pg-btn.active {
    background: var(--p);
    color: var(--btn-text);
    border-color: var(--p);
}

.sw-pg-btn:hover:not(.active) {
    background: var(--b2);
}

@keyframes sw-fade-in {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.sw-fade-in {
    animation: sw-fade-in 0.22s ease-out both;
}
```

---

## Modal con botón cerrar — HTML

Abrir y cerrar **siempre** con `swOpenModal(id)` / `swCloseModal(id)` de
`soffi-ui.js`. Nunca con `style.display` a mano ni con `data-toggle="modal"`
(no hay Bootstrap en el proyecto).

```html
<button type="button" class="sw-btn sw-btn-primary" onclick="swOpenModal('exampleModal')">
    Abrir
</button>

<div class="sw-modal-backdrop" id="exampleModal">
    <div class="sw-modal" role="dialog" aria-modal="true" aria-labelledby="exampleModalTitle" tabindex="-1">
        <div class="sw-modal-title" id="exampleModalTitle">
            <span>Título del modal</span>
            <button type="button" class="sw-modal-close" onclick="swCloseModal('exampleModal')" aria-label="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="sw-modal-body">
            Contenido del modal aquí. Visible en ambos temas.
        </div>
        <div class="sw-modal-actions">
            <button class="sw-btn sw-btn-ghost" onclick="swCloseModal('exampleModal')">
                Cancelar
            </button>
            <button class="sw-btn sw-btn-primary">
                Confirmar
            </button>
        </div>
    </div>
</div>
```

Dentro de un formulario parcial reutilizado, cerrar sin hardcodear el id:

```html
<button type="button" class="sw-btn sw-btn-ghost"
        onclick="swCloseModal(this.closest('.sw-modal-backdrop').id)">
    Cerrar
</button>
```

**Notas:**
- La visibilidad la controla la clase `.is-open`, no `style.display`.
- `swOpenModal()` mueve el modal a `<body>`: dentro de `.sw-main` (que anima
  opacidad) un `position: fixed` queda atrapado bajo el topbar.
- El clic en el backdrop y la tecla `Escape` cierran el modal.
- Ancho: `.sw-modal-sm` para confirmaciones, `.sw-modal` por defecto y
  `.sw-modal-lg` para formularios o contenido amplio.
- El foco pasa al primer campo al abrir, queda atrapado dentro del modal y
  vuelve al botón que lo abrió al cerrar.
- El backdrop y `Escape` cierran por defecto. Para un modal obligatorio usar
  `data-modal-backdrop="static"` y/o `data-modal-escape="false"`.
- La X (`.sw-modal-close`) usa `var(--bc)` que cambia automático light/dark
- Hover a color primary (`var(--p)`)
- Icono con Font Awesome `fa-xmark`
- `justify-content: space-between` en título alinea X a derecha

---

## Collapse / acordeón — HTML

```html
<button type="button" class="sw-btn sw-btn-ghost sw-btn-sm"
        data-toggle="collapse" data-target="#detalle-1" aria-expanded="false">
    <i class="fa fa-chevron-down"></i>
    <span class="sw-ml-2">Ver detalle</span>
</button>

<div id="detalle-1" class="collapse" data-parent="#acordeon">
    ...
</div>
```

- Lo maneja `swToggleCollapse()` vía listener global; no requiere `onclick`.
- El panel arranca oculto salvo que traiga `.show`.
- `data-parent` en el panel lo vuelve acordeón (cierra a sus hermanos).
- `.collapse` / `.show` conservan el nombre sin prefijo `sw-` por
  compatibilidad con las vistas que ya los usaban.

---

## Validación de formularios

Marcar el `<form>` y dejar que `soffi-ui.js` haga el resto:

```html
<form action="..." method="post" class="needs-validation" novalidate>
    <div class="sw-field">
        <label class="sw-label" for="celular">Celular <span class="sw-required">*</span></label>
        <input type="text" id="celular" name="celular" class="sw-input"
               required pattern="[0-9]{7,15}" title="Debe tener entre 7 y 15 dígitos">
        @error('celular')
            <small class="sw-hint-error">{{ $message }}</small>
        @enderror
    </div>
</form>
```

- Valida al enviar; pinta `sw-error` en el campo y agrega un
  `<small class="sw-hint-error">` con el mensaje, igual que un error de servidor.
- Mensajes en español, generados desde `field.validity`.
- `title` se usa como mensaje cuando falla el `pattern`.
- `novalidate` es obligatorio: sin él el navegador muestra su propio tooltip.

---

# Componentes nuevos en 0.3.0 (unificación de las 4 apps)

Estos venían de una sola app y pasan al estándar.

## Toast — `.sw-alert-toast` (venía de SoffiFac)

Aviso corto que flota sobre el contenido y se cierra solo. **Distinto de
`.sw-alert`**, que es un bloque fijo que se queda en el flujo de la página.

```html
<div class="sw-alert sw-alert-success sw-alert-toast">
    <i class="fa-solid fa-circle-check"></i>
    Producto creado correctamente
</div>
```

`swInitAlerts()` (en `soffi-ui.js`, corre solo en `DOMContentLoaded`) le agrega
el botón de cerrar, lo auto-cierra a los 7s (`SW_ALERT_TIMEOUT`) y apila varios
toasts con `swStackAlerts()`.

Cuándo usar cuál:

| Caso | Clase |
|---|---|
| "Guardado correctamente" | `.sw-alert .sw-alert-toast` |
| Lista de errores de validación | `.sw-alert` solo |
| Mensaje largo o contextual | `.sw-alert` solo |

> Sofficon puede seguir usando `sweetalert2`: el override canónico vive en
> `vendor-sweetalert2.css` y se incluye en `soffi-ui.css`.

## Banner de impersonación — `.sw-impersonation-banner` (venía de SoffiFac)

La barra que avisa "estás viendo el sistema como Juan Pérez — salir".

```html
<div class="sw-impersonation-banner">
    <span class="sw-impersonation-banner-text">
        <i class="fa-solid fa-user-secret"></i>
        Estás viendo el sistema como <strong>{{ $usuario->name }}</strong>
    </span>
    <a href="{{ route('impersonate.leave') }}" class="sw-btn sw-impersonation-banner-exit">
        Salir
    </a>
</div>
```

No lleva la clase `.sw-alert` a propósito: debe quedarse visible mientras dure
la sesión impersonada, así que no entra en `swInitAlerts()` ni se auto-cierra.

## Totales anclados — `.sw-table-totals` (venía de gesnom `dev`)

Modificador opt-in: tabla que cierra con una fila de sumas en el `tfoot`.
Dentro de un `.sw-list-table-wrap` los totales quedan anclados al pie mientras
se scrollea.

```html
<div class="sw-list-table-wrap">
    <table class="sw-table sw-table-totals">
        <tbody>...</tbody>
        <tfoot>
            <tr>
                <th class="sw-table-total-label" colspan="3">Totales del rol</th>
                <td>1 234,56</td>
            </tr>
        </tfoot>
    </table>
</div>
```

La tabla normal no lleva la clase y su `tfoot` queda sin tratamiento.

## Resumen de rol — `.sw-rol-summary` (venía de gesnom `dev`)

Banda compacta con el contexto vigente (rol, periodo) sobre un listado.

```html
<div class="sw-rol-summary">
    <div class="sw-rol-summary-main">
        <span class="sw-rol-summary-tag">Rol vigente</span>
        <h2 class="sw-rol-summary-title">Nómina junio 2026</h2>
    </div>
    <div class="sw-rol-summary-stats">
        <div class="sw-rol-summary-stat">
            <span class="sw-rol-summary-label">Empleados</span>
            <span class="sw-rol-summary-value">42</span>
        </div>
        <div class="sw-rol-summary-stat">
            <span class="sw-rol-summary-label">Total</span>
            <span class="sw-rol-summary-value">18 420,00 <span class="sw-rol-summary-unit">USD</span></span>
        </div>
    </div>
</div>
```

## Regla anti doble-espaciado (venía de SoffiFac)

```css
.sw-mb-3 > .sw-field,
.sw-mb-4 > .sw-field { margin-bottom: 0; }
```

Evita el hueco doble en el patrón `sw-col-* sw-mb-3 > sw-field`: la columna ya
aporta el margen, el `.sw-field` no debe sumar el suyo encima.

---

# Utilidades nuevas en 0.11.0

Componentes sin dependencias externas. Todos soportan tema claro y oscuro.

## Dropdown — `.sw-dropdown*`

```html
<div class="sw-dropdown">
    <button type="button" aria-haspopup="true" aria-expanded="false"
            aria-controls="menu-acciones" onclick="swToggleDropdown(this)">
        Acciones
    </button>
    <div id="menu-acciones" class="sw-dropdown-menu" role="menu">
        <a class="sw-dropdown-item" href="/perfil" role="menuitem">Perfil</a>
        <button class="sw-dropdown-item" type="button" role="menuitem">Exportar</button>
    </div>
</div>
```

`.sw-dropdown-menu-start` alinea el menú a la izquierda. El JS cierra menús
abiertos con clic externo o `Escape`. `.sw-dropdown-divider` dibuja una línea
de separación entre grupos y agrega espacio vertical intencional.

## Spinner — `.sw-spinner*`

Indicador de carga. Variantes: `.sw-spinner-sm`, `.sw-spinner-lg` y
`.sw-spinner-light` para fondos oscuros.

```html
<span class="sw-spinner" role="status" aria-label="Cargando"></span>
```

## Tooltip — `.sw-tooltip`

Tooltip CSS para texto corto. El elemento debe ser enfocable si contiene
información necesaria para teclado.

```html
<button type="button" class="sw-tooltip" data-tooltip="Guardar cambios"
        aria-label="Guardar cambios">💾</button>
<span class="sw-tooltip sw-tooltip-bottom" data-tooltip="Ayuda" tabindex="0">?</span>
```

## Switch — `.sw-switch*`

Control booleano basado en checkbox nativo. Mantener `label` asociado para
preservar teclado y accesibilidad.

```html
<label class="sw-switch">
    <input class="sw-switch-input" type="checkbox" name="activo">
    <span class="sw-switch-track" aria-hidden="true"><span class="sw-switch-thumb"></span></span>
    <span class="sw-switch-label">Cuenta activa</span>
</label>
```

## Skeleton — `.sw-skeleton*`

Placeholder para contenido que todavía carga. Variantes: `.sw-skeleton-text`,
`.sw-skeleton-title`, `.sw-skeleton-avatar` y `.sw-skeleton-media`.

Para una composición estable, usar `.sw-skeleton-card`,
`.sw-skeleton-layout`, `.sw-skeleton-row` y `.sw-skeleton-content`. El ancho
de la tarjeta se configura con `--sw-skeleton-card-width`; cada placeholder
acepta `--sw-skeleton-width` y `--sw-skeleton-height`.

```html
<div class="sw-card sw-skeleton-card" aria-busy="true" aria-label="Cargando">
    <div class="sw-skeleton-layout">
        <div class="sw-skeleton-row">
            <span class="sw-skeleton sw-skeleton-avatar" aria-hidden="true"></span>
            <div class="sw-skeleton-content">
                <span class="sw-skeleton sw-skeleton-title" aria-hidden="true"></span>
                <span class="sw-skeleton sw-skeleton-text" aria-hidden="true"></span>
            </div>
        </div>
        <span class="sw-skeleton sw-skeleton-media" aria-hidden="true"></span>
    </div>
</div>
```

## Stepper — `.sw-stepper*`

Flujo de pasos. Marcar paso actual con `.is-active`, pasos terminados con
`.is-complete` y usar `.sw-stepper-vertical` para orientación vertical.

```html
<ol class="sw-stepper" aria-label="Progreso">
    <li class="sw-step is-complete"><span class="sw-step-marker">1</span><span class="sw-step-content"><strong class="sw-step-title">Datos</strong></span></li>
    <li class="sw-step is-active"><span class="sw-step-marker">2</span><span class="sw-step-content"><strong class="sw-step-title">Confirmación</strong></span></li>
</ol>
```

## Impresión — `.sw-print-*`

En impresión se ocultan topbar, sidebar, footer y `.sw-print-hide`. Mostrar
contenido exclusivo con `.sw-print-only`; separar páginas con
`.sw-print-break-before`, `.sw-print-break-after` o `.sw-print-avoid-break`.

```html
<button class="sw-print-hide" type="button" onclick="window.print()">Imprimir</button>
<p class="sw-print-only">Documento generado para impresión</p>
```

El paquete incluye los estilos `@media print`; no requiere JavaScript.

## Actualizaciones 0.11.1–0.11.3

- Cada utilidad tiene página propia en la galería y navegación anterior/siguiente.
- `.sw-dropdown-divider` usa una línea real (`border-top`) y conserva separación
  vertical entre grupos.
- Skeleton composable: `.sw-skeleton-card`, `.sw-skeleton-layout`,
  `.sw-skeleton-row`, `.sw-skeleton-content`, `--sw-skeleton-card-width`,
  `--sw-skeleton-width` y `--sw-skeleton-height`.
- `.sw-row-menu-dropdown` se portaliza a `<body>` mediante JS cuando abre. Esto
  evita recorte por `overflow` y mantiene el menú sobre columnas sticky.
- `.is-table-sticky-displaced` muestra el separador de la columna sticky cuando
  todavía existe desplazamiento horizontal.

La lógica `gal*` usada en la galería es demo; aplicaciones deben usar la API
`sw*` del paquete.

---

# Componentes pendientes

| Componente | Prioridad | Estado |
|---|---|---|
| Override de sweetalert2 | alta | pendiente |
| Accordion visual | media | pendiente; existe `swToggleCollapse` como comportamiento |

Estos pendientes bloquean la declaración de `1.0.0`. No crear overrides locales
para cerrarlos: deben entrar al paquete, su documentación y sus pruebas visuales.

Ninguno requiere features por encima de Chrome 109 — ver [compat.md](compat.md).

---

## Action card — `.sw-action-card` (nuevo)

Icono arriba, título, descripción, botón de ancho completo. Ninguna de las 4
apps lo tenía — lo más cercano era `.sw-quicklink` (horizontal, sin botón).
Pensado para pantallas de selección de acción: "Importar datos", accesos a
módulos, selección de tipo de operación.

```html
<div class="sw-action-card-grid">
    <div class="sw-action-card">
        <div class="sw-action-card-icon sw-action-card-icon-primary">
            <i class="fa-solid fa-building"></i>
        </div>
        <h3 class="sw-action-card-title">Departamentos</h3>
        <p class="sw-action-card-desc">
            Importa o actualiza la lista de departamentos desde Excel/CSV.
        </p>
        <a href="{{ route('import.departamentos') }}" class="sw-btn sw-btn-primary sw-action-card-action">
            <i class="fa-solid fa-file-import"></i> Ir a importación
        </a>
    </div>
</div>
```

Variantes de color del icono (mismo criterio que `.sw-metric-icon-*`):
`-primary`, `-secondary`, `-accent`, `-success`, `-warning`, `-info`.

Deshabilitada (ej. "Próximamente"): agregar `.sw-action-card-disabled` a
`.sw-action-card` y usar un `<span>` en vez de `<a>` para la acción — no hay
JS que dependa de la clase, es solo opacidad.

## Insignia que se reduce a icono

En listados con muchas columnas, una insignia puede quedar solo con el icono
cuando la tabla, con las insignias completas, no cabe en el ancho visible:

```html
<span class="sw-badge sw-badge-collapsible sw-badge-success" title="Activa">
    <i class="fa-solid fa-circle"></i>
    <span class="sw-badge-label">Activa</span>
</span>
```

Requisitos: la tabla debe ir dentro de `.sw-list-table-wrap`, el texto dentro
de `.sw-badge-label` y conviene un `title` (el texto sigue disponible para
lectores de pantalla). Reacciona al ancho del listado, no al de la ventana, vía
`swSyncCollapsibleBadges()` (se conecta solo al cargar; llamarla tras inyectar
contenido dinámico). Sin JS la insignia conserva el texto.

