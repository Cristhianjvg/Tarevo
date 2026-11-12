---
name: soffi-ui
description: "Aplica Soffi UI en tareas de UI, CSS, JavaScript, React o Laravel + Blade: tokens, componentes sw-*, layout multi-tenant, tablas, formularios, modales, dropdowns, estados de carga, accesibilidad y compatibilidad. Usar cuando una app Soffiweb necesite crear, refactorizar, auditar o documentar una interfaz. No usa Bootstrap, Popper, Tailwind ni otro framework CSS."
license: MIT
metadata:
  author: soffiweb
  package: soffiweb/soffi-ui
  version: "1.2.1"
---

# Soffi UI — sistema de diseño Soffiweb

## La fuente única

El paquete **`soffiweb/soffi-ui`** es la verdad absoluta del sistema visual.
Esta skill se genera desde ahí y viaja con él.

Antes de crear o refactorizar vistas, leer las fuentes del paquete:

| Archivo | Qué define |
|---|---|
| `src/tokens/color.css` | paleta, tema oscuro, bordes |
| `src/tokens/spacing.css` | espaciado, radios, sombras |
| `src/tokens/typography.css` | familia, escala de tamaños, pesos |
| `src/tokens/z-index.css` | capas (topbar, sidebar, modal, toast) |
| `src/base/reset.css` | reset + resets de markup heredado |
| `src/base/utilities.css` | utilidades (`sw-mt-*`, `sw-d-flex`, `sw-text-*`…) |
| `src/components/*.css` | 37 archivos, un componente o familia por archivo |
| `js/soffi-ui.js` | 41 funciones, vanilla |

`dist/` y `public/vendor/soffi-ui/` son **salida de build**: el próximo
`php scripts/build.php` los sobrescribe. Todo cambio va a `src/`.

Si una app tiene su propia copia de `soffi-ui/`, **está desactualizada por
definición**: el paquete es el origen. No editar la copia.

## Uso con Claude Code y Codex

Esta carpeta es la fuente portable de la skill. Distribuirla completa: `SKILL.md`
y todos sus capítulos. No aplanar archivos ni copiar solo el manifiesto.

Primeros pasos para crear agentes, instalar la skill y usar estilos: leer
[getting-started.md](getting-started.md).

| Entorno | Ruta esperada | Activación |
|---|---|---|
| Claude Code | `.claude/skills/soffi-ui/` | `$soffi-ui` o tarea de UI que coincida con la descripción |
| Codex en un repositorio | `.codex/skills/soffi-ui/` | `$soffi-ui` o coincidencia semántica |
| OpenAI Agent Skills | `.agents/skills/soffi-ui/` | registro del directorio padre como capability directory |

`CLAUDE.md` y `AGENTS.md` contienen reglas del repositorio. Esta skill contiene
el procedimiento reusable de diseño. No duplicar reglas de negocio, rutas o
nombres de apps dentro de la skill.

Los enlaces relativos deben conservarse al mover la carpeta. Debe existir un
solo `SKILL.md` dentro del bundle y el frontmatter debe conservar `name` y
`description`.

## Instalación y actualización

El paquete se consume por Composer. No se copia a mano a la app — copiarlo fue
la causa raíz de que las 4 apps divergieran.

```bash
composer config repositories.soffi-ui vcs https://github.com/Soffiweb/soffi-ui
composer require soffiweb/soffi-ui

php artisan vendor:publish --tag=soffi-ui --force         # CSS/JS (obligatorio)
php artisan vendor:publish --tag=soffi-ui-skills --force  # esta skill
```

| Tag | Qué publica |
|---|---|
| `soffi-ui` | `dist/soffi-ui.{css,js}` → `public/vendor/soffi-ui/` |
| `soffi-ui-blade` | layout y componentes → `resources/views/vendor/soffi-ui/` |
| `soffi-ui-skills` | esta skill → `.claude/skills/` y `.agents/skills/` |

Para actualizar: `composer update soffiweb/soffi-ui` y repetir los publish con
`--force`. La versión queda fijada en `composer.lock`.

Para Codex repo-scoped, conservar además la carpeta en
`.codex/skills/soffi-ui/`. No publicar una copia parcial.

## Regla maestra

Toda interfaz usa ÚNICAMENTE los tokens definidos aquí. Sin colores hex
inventados. Sin fuentes distintas a Inter. Sin gradientes decorativos.

Cada componente nuevo o refactorizado debe quedar correcto en modo claro **y**
oscuro: fondos, textos, bordes, hover/focus, inputs, tablas, modales, badges,
dropdowns y header.

Las vistas consumen componentes `sw-*` existentes; no los redefinen ni crean
variantes locales. Si falta un patrón reusable, **agregarlo primero al
paquete**, publicarlo y después usarlo en la vista.

No renderizar métricas, totales o cards-resumen por defecto. Solo si el
requerimiento las pide.

## Regla de compatibilidad — leer antes de escribir CSS

El sistema corre en computadoras antiguas. El piso es **Chrome/Edge 109 ·
Firefox 115 ESR · Safari 15.6** (Chrome 109 es la última versión que corre en
Windows 7/8/8.1).

`color-mix()` y `oklch()` llegaron en Chrome 111 — por encima del piso.

- **Todo `color-mix()` lleva su valor estático en la línea anterior.**
- **`oklch()` está prohibido.** Los tokens de color van en hex.
- En `:root` la doble declaración no sirve: usar `@supports`.

Está todo en **[compat.md](compat.md)**, con la tabla de features permitidas y
cómo calcular cada fallback. Es obligatorio.

El check `php scripts/check-compat.php` **falla el build** si algo lo viola. No
es honor system: la convención escrita ya falló una vez (204 declaraciones sin
fallback acumuladas entre SoffiFac y gesnom `dev`).

## Capítulos

| Archivo | Contenido |
|---|---|
| [agents.md](agents.md) | distribución, activación y validación en Claude Code, Codex y Agent Skills |
| [getting-started.md](getting-started.md) | creación y uso de agentes, componentes y estilos |
| [compat.md](compat.md) | **piso de navegador y reglas de fallback — empezar acá** |
| [tokens.md](tokens.md) | paleta, tipografía, espaciado, radios, breakpoints |
| [layout.md](layout.md) | shell, topbar (tenant/periodo), menú de usuario, sidebar, footer |
| [components.md](components.md) | catálogo completo de componentes `sw-*` |
| [js.md](js.md) | las 41 funciones, y qué hacer en React |
| [auth.md](auth.md) | pantallas de login / selección de empresa / recuperar clave |
| [laravel.md](laravel.md) | **integración Laravel + Blade — leer para cualquier vista** |
| [view-patterns.md](view-patterns.md) | patrones de vistas CRUD, listados, formularios |

Las apps Soffiweb son Laravel + Blade: para tocar una vista se leen
`laravel.md` y `view-patterns.md` además de los capítulos de CSS.

## Flujo obligatorio

1. Identificar si el cambio afecta tokens, componente, JS, layout, Blade o React.
2. Leer el capítulo correspondiente antes de escribir código.
3. Reutilizar `sw-*` y la API compartida; no crear equivalentes locales.
4. Validar tema claro/oscuro, teclado, foco y piso de navegador.
5. Si cambia `src/` o `js/`, ejecutar `composer check-compat`, `composer test` y
   `composer build`; nunca editar `dist/` manualmente.
6. Si solo cambia documentación, validar enlaces relativos y frontmatter.

## Familias de componentes estándar

Usar estas familias antes de crear clases nuevas:

- **Página**: `sw-crud-page`, `sw-crud-container`, `sw-crud-head`, `sw-crud-title`, `sw-crud-actions`.
- **Layout base**: `sw-app`, `sw-body`, `sw-main`, `sw-footer`, `sw-footer-links` (topbar y sidebar tienen su propia familia, ver `layout.md`).
- **Filtros/listas**: `sw-list-card`, `sw-list-toolbar`, `sw-list-search`, `sw-list-filter-row`, `sw-list-filter-control` (`-sm` para selectores cortos), `sw-list-filter-actions`, `sw-list-meta`, `sw-list-count`.
- **Tablas**: `sw-table-wrap`, `sw-list-table-wrap` (altura acotada + header sticky), `sw-table-sticky-col` (columna fija horizontal en todos los anchos; agregar al `th` y `td` de la columna elegida), `sw-table` (se densifica sola hasta 1280px; usarla en listados), `sw-table-compact` (densa en todos los anchos; solo reportes con muchas columnas), `sw-table-striped`, `sw-table-min-md/lg/xl`, `sw-table-totals` (fila de sumas anclada), `sw-table-actions`, `sw-bico`, `sw-row-menu*`, `sw-money`.
- **Estados y formularios**: `sw-alert` (+ `sw-alert-toast` para avisos efímeros), `sw-empty-state`, `sw-field`, `sw-label`, `sw-input`, `sw-select`, `sw-textarea`, `sw-error`, `sw-required`, `sw-hint`, `sw-hint-error`, `sw-form-actions`.
- **Navegación**: `sw-breadcrumb*`, `sw-pagination`, `sw-pg-btn`, `sw-pg-ellipsis`, `sw-pagination-summary`.
- **Tabs/paneles**: `sw-panel-tabs`, `sw-panel-card`, `sw-panel-tab-pane`, `sw-tabs`, `sw-tab`.
- **Resumen/datos**: `sw-section-block`, `sw-section-title`, `sw-data-grid`, `sw-data-item`, `sw-data-label`, `sw-data-value`, `sw-total-panel`, `sw-rol-summary*`.
- **Dashboard**: `sw-metric*`, `sw-quicklink*`, `sw-chart-card`, `sw-donut*`, `sw-activity*`.
- **Sesión**: `sw-impersonation-banner*`.
- **Utilidades**: `sw-dropdown*`, `sw-spinner*`, `sw-tooltip`, `sw-switch*`, `sw-skeleton*`, `sw-stepper*`, `sw-print-*`.

Catálogo completo en [components.md](components.md). El estado de pendientes
rumbo a `1.0.0` está documentado abajo; no declarar esa versión todavía.

## Estado actual y preparación para 1.0.0

Versión actual del paquete: `0.11.3`. La galería ya cubre el flujo de
empleados con modal de alta, dropdown de acciones, skeleton, spinner, refresh
y tablas con menú de fila portalizado. `gal*` pertenece a la galería; no es API
del paquete ni debe copiarse a una app.

Cambios de la serie actual documentados en esta skill:

- `0.11.0`: dropdown, spinner, tooltip, switch, skeleton, stepper e impresión.
- `0.11.1`: páginas independientes de utilidades y navegación sincronizada.
- `0.11.2`: skeleton composable con variables de tamaño y divider de dropdown.
- `0.11.3`: menús de fila por encima de columnas sticky y contenedores con overflow.

Bloqueadores antes de `1.0.0`:

- retrofit de `font-size` inline heredado en Sofficon, con QA visual;
- pruebas visuales o snapshots para los dos temas y layouts principales.

SweetAlert2 y accordion visual ya forman parte del paquete; queda publicar los
artefactos actualizados en las apps y validar el retrofit de Sofficon.

La skill no debe cambiar `VERSION`, `CHANGELOG.md`, tags ni `metadata.version`
como parte de una actualización documental.

## Capa Blade del paquete

Para **proyectos nuevos**, el paquete trae layout y componentes Blade genéricos,
data-driven, y evita copiar el chrome a mano:

```blade
@extends('soffi-ui::layouts.panel')

@section('sidebar')
    <x-sw-sidebar :items="$menu" :brand="config('app.name')" brand-sub="Sistema de gestión" />
@endsection

@section('breadcrumb')
    <x-sw-breadcrumb :items="[['label' => 'Socios', 'active' => true]]" />
@endsection
```

El menú entra por props: el paquete da el chrome, cada app su árbol de
navegación. No meter lógica de negocio (roles, rutas) en los componentes.

Las 4 apps existentes (SoffiFac, SoffCaja, Sofficon, gesnom) **no** usan esta
capa: tienen su layout propio copiado. Para ellas el markup de referencia del
topbar, sidebar y footer está en [laravel.md](laravel.md) — es un contrato de
markup, no un paso de instalación.
