# Soffiweb UI - Shared JS

`soffi-ui.js` es la única capa de interactividad del sistema. Reemplaza por
completo a Bootstrap JS: modales, tabs, collapse y validación de formularios
son propios.

> **Fuente única:** `js/soffi-ui.js` en el paquete `soffiweb/soffi-ui`.
> Nunca editar `public/vendor/soffi-ui/soffi-ui.js` a mano: es la salida de
> `php scripts/build.php` y el próximo `vendor:publish --force` la sobrescribe.
> Este documento describe el contrato de uso, no duplica el código.

---

## Dependencias

Ninguna. Es JS nativo, sin jQuery. (jQuery solo se carga si el proyecto usa
select2.)

---

## API

### Tema

| Función | Uso |
| --- | --- |
| `swToggleTheme()` | Alterna claro/oscuro y persiste en `localStorage`. Al cargar restaura el tema guardado. |

```html
<button type="button" onclick="swToggleTheme()" aria-label="Cambiar tema">
    <i class="fa fa-adjust"></i>
</button>
```

### Acciones de cabecera con desborde

| Atributo | Uso |
| --- | --- |
| `data-sw-overflow="N"` en `.sw-crud-actions` | Por debajo de 992px, deja visibles N botones (el menú "Más acciones" cuenta como uno) y mueve el resto a ese menú. En pantallas grandes los devuelve a su lugar. |

Cuenta los hijos directos `.sw-btn` y `.sw-dropdown`. Si ya hay un `.sw-dropdown`, le agrega los botones sobrantes; si no, crea uno. Los botones `.sw-btn-primary` nunca se mueven, sin importar su posición. Los botones se mueven (no se clonan), así que conservan sus `onclick`. Ejemplo: Agregar + PDF + Excel con `data-sw-overflow="2"` queda en celular como Agregar + Más acciones (PDF, Excel). Con el atributo ausente no cambia nada.

```html
<div class="sw-crud-actions sw-crud-actions-row" data-sw-overflow="2">
    <button type="button" class="sw-btn sw-btn-primary">Agregar</button>
    <button type="button" class="sw-btn sw-btn-ghost">Reporte PDF</button>
    <button type="button" class="sw-btn sw-btn-ghost">Reporte Excel</button>
</div>
```

### Sidebar y menú de usuario

| Función | Uso |
| --- | --- |
| `swToggleSidebar(trigger)` | Abre/cierra el sidebar como overlay (<1200px). En escritorio lo contrae/expande (`body.sw-sidebar-collapsed`) y recuerda la elección en `localStorage`. |
| `swCollapseSidebar()` | Para la flecha `.sw-sb-close` del sidebar: contrae en escritorio, cierra el overlay en celular y tablet. |
| `swCloseSidebar()` | Cierra el sidebar. Se llama solo al navegar (<1200px) y al pulsar el backdrop. |
| `swToggleSub(trigger)` | Abre/cierra un submenú `.sw-sb-sub`. El trigger debe ser `<button aria-expanded aria-controls>`. |
| `swToggleUserMenu(trigger)` | Abre el dropdown indicado por `aria-controls`. |
| `swCloseUserMenu(restoreFocus)` | Cierra el dropdown. Se llama solo por clic externo y `Escape`. |

### Menu de acciones secundarias por fila

| Función | Uso |
| --- | --- |
| `swToggleRowMenu(trigger)` | Abre/cierra el panel `.sw-row-menu-dropdown` de esa fila. Cierra cualquier otro abierto. |
| `swCloseAllRowMenus()` | Cierra todos. Se llama sola por clic externo y `Escape`. |

```html
<div class="sw-row-menu">
    <button type="button" class="sw-bico sw-row-menu-trigger" aria-haspopup="true" aria-expanded="false"
            title="Más acciones" aria-label="Más acciones" onclick="swToggleRowMenu(this)">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>
    <div class="sw-row-menu-dropdown">
        <a href="..." class="sw-row-menu-item"><i class="fa-solid fa-receipt"></i> Ticket POS</a>
        <a href="..." class="sw-row-menu-item"><i class="fa-solid fa-file-invoice-dollar"></i> Retención</a>
    </div>
</div>
```

Usar cuando una fila de tabla necesita más de ~4 acciones: mantener las
principales como `sw-bico` sueltos y mover el resto a este menú, en vez de
apilar iconos hasta que la columna quede confusa.

Al abrirse, el menú se porta temporalmente a `<body>` y usa posición fija.
Así queda por encima de columnas sticky y no se recorta por el `overflow` de
`.sw-list-table-wrap`.

### Tablas interactivas

| Función | Uso |
| --- | --- |
| `swInitTableStickyColumns()` | Conecta el scroll horizontal de cada listado y calcula el indicador de desplazamiento. Se llama automáticamente al cargar. |
| `swSyncTableStickyColumns()` | Actualiza `.is-table-sticky-displaced`; usar después de mostrar contenido cargado dinámicamente. |
| `swToggleTableRow(trigger)` | Abre una fila detalle indicada por `data-target` y cierra las demás de la misma tabla. |
| `swToggleTableSelection(master)` | Marca o desmarca todos los `.sw-checkbox-input` del `tbody`. |
| `swSyncTableSelection(table)` | Sincroniza `checked` e `indeterminate` del checkbox maestro. |

La columna fija requiere `.sw-table-sticky-col` en su `th` y sus `td`. El
contenedor usa `.sw-list-table-wrap`; no aplicar `position: sticky` local.
La fijación horizontal funciona en todos los anchos, también en móvil, para
que las acciones sigan alcanzables; la cabecera conserva su fijación vertical.

Helpers automáticos: `swSyncSelectColor()` para selects vacíos,
`swSyncAuthInputGroup()` para errores de auth, `swSyncDateInputColor()` para
inputs de fecha/hora y `swFileInputName()` para el nombre de archivos.

### Dropdown genérico

| Función | Uso |
| --- | --- |
| `swToggleDropdown(trigger)` | Abre o cierra el menú indicado por `aria-controls`. |
| `swCloseDropdowns(restoreFocus)` | Cierra menús genéricos y, opcionalmente, devuelve el foco al trigger. |

```html
<div class="sw-dropdown">
    <button type="button" aria-haspopup="true" aria-expanded="false"
            aria-controls="acciones" onclick="swToggleDropdown(this)">
        Acciones
    </button>
    <div id="acciones" class="sw-dropdown-menu" role="menu">
        <a class="sw-dropdown-item" href="/perfil" role="menuitem">Perfil</a>
        <button class="sw-dropdown-item" type="button" role="menuitem">Exportar</button>
    </div>
</div>
```

El menú se cierra con clic externo o `Escape`. Usar `.sw-dropdown-menu-start`
para alinearlo al borde izquierdo.

### Modales

| Función | Uso |
| --- | --- |
| `swOpenModal(id)` | Abre el modal (agrega `.is-open`). |
| `swCloseModal(id)` | Cierra ese modal. |
| `swCloseAllModals()` | Cierra todos. Se llama solo con `Escape`. |

Contrato del markup:

```html
<div class="sw-modal-backdrop" id="miModal">
    <div class="sw-modal sw-modal-lg" role="dialog" aria-modal="true" aria-labelledby="miModalTitle">
        <div class="sw-modal-title" id="miModalTitle">
            <div>
                <div>Título</div>
                <p class="sw-card-body">Subtítulo opcional.</p>
            </div>
            <button type="button" class="sw-modal-close" onclick="swCloseModal('miModal')" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form action="..." method="post">
            @csrf
            @include('modulo.form')
        </form>
    </div>
</div>
```

Reglas:

- **Nunca** `data-toggle="modal"` / `data-dismiss="modal"`: no hay Bootstrap.
- `swOpenModal()` reubica el modal como hijo directo de `<body>`. Es necesario:
  `.sw-main` anima opacidad y crea su propio stacking context, así que un
  `position: fixed` anidado ahí queda atrapado bajo el topbar sin importar el
  `z-index`.
- El clic en el backdrop cierra el modal (listener global).
- Para poblar un modal de edición, escribir los valores **antes** de
  `swOpenModal()`; ver "Modal de edición" abajo.

### Tabs

| Función | Uso |
| --- | --- |
| `swActivateTab(event, link)` | Activa la pestaña y su panel. |

```html
<ul class="sw-panel-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" href="#panel-1" role="tab" onclick="swActivateTab(event, this)">Pestaña 1</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active sw-panel-tab-pane" id="panel-1" role="tabpanel">...</div>
</div>
```

### Collapse / acordeón

| Función | Uso |
| --- | --- |
| `swToggleCollapse(trigger)` | Abre/cierra el target. Se dispara solo vía listener global sobre `[data-toggle="collapse"]`. |

```html
<button type="button" data-toggle="collapse" data-target="#detalle-1" aria-expanded="false">
    <i class="fa fa-chevron-down"></i> Ver detalle
</button>

<div id="detalle-1" class="collapse" data-parent="#acordeon">...</div>
```

- El panel arranca oculto salvo que tenga `.show`.
- `data-parent` en el **panel** lo vuelve acordeón: al abrir uno se cierran sus
  hermanos dentro de ese contenedor.
- Para un accordion visual, combinarlo con `.sw-accordion`,
  `.sw-accordion-trigger` y `.sw-accordion-panel`; el CSS del componente ya
  resuelve estados, foco, icono y tema oscuro.
- La función actualiza `aria-expanded` en el trigger; útil para rotar el chevron:
  `[data-toggle="collapse"][aria-expanded="true"] .fa-chevron-down { transform: rotate(180deg); }`
- Se conservan los nombres `.collapse` / `.show` (no `sw-`) porque son los que
  las vistas ya usaban cuando esto lo manejaba Bootstrap.

### Validación de formularios

| Función | Uso |
| --- | --- |
| `swValidateField(field)` | Valida un campo, aplica `sw-error` y escribe el mensaje. Devuelve `true`/`false`. |
| `swValidationMessage(field)` | Arma el texto en español según `field.validity`. |

Se engancha solo a todo `form.needs-validation` al cargar la página:

```html
<form action="..." method="post" class="needs-validation" novalidate>
```

Comportamiento:

- Valida **solo al enviar**, no mientras se escribe.
- Al campo inválido le pone `sw-error` (borde rojo) e inserta un
  `<small class="sw-hint-error">` justo después, igual que los errores de
  servidor de `@error()`. Si ya existe uno, lo reutiliza.
- Los mensajes se generan en español a partir de `field.validity`
  (`valueMissing`, `patternMismatch`, `tooShort`…). **No** se usa
  `field.validationMessage`, que sale en el idioma del navegador.
- En `patternMismatch` usa el atributo `title` del input si existe. Aprovecharlo
  para mensajes precisos:
  `<input pattern="[0-9]{7,15}" title="Debe tener entre 7 y 15 dígitos">`
- `novalidate` es obligatorio: suprime el tooltip nativo para que el mensaje lo
  pinte el sistema de diseño.

### Inputs

| Función | Uso |
| --- | --- |
| `swFileInputName(input)` | Muestra el nombre del archivo elegido en `.sw-file-input-name`. |
| `swSyncDateInputColor(input)` | Colorea `mm/dd/yyyy` como placeholder mientras el date esté vacío. Se aplica solo en `input`, al cargar y al abrir un modal. |

### Select buscador propio

| Función | Uso |
| --- | --- |
| `swToggleSS(id)` | Abre/cierra el dropdown. |
| `swSelectOpt(id, option)` | Marca la opción y cierra. |
| `swFilterSS(id, query)` | Filtra opciones; muestra "Sin resultados" si no hay match. |

---

## Modal de edición: el patrón correcto

Un formulario incluido en dos modales (crear y editar) **duplica los `id`**, y
`getElementById` siempre devuelve el primero — el de crear. El modal de editar
abriría vacío.

Usar siempre `$formIdPrefix`:

```blade
{{-- modulo/form.blade.php --}}
@php($formIdPrefix = $formIdPrefix ?? 'modulo')

<input type="text" id="{{ $formIdPrefix }}-descripcion" name="descripcion" class="sw-input">
<label for="{{ $formIdPrefix }}-descripcion">Descripción</label>
```

```blade
{{-- modulo/index.blade.php --}}
<form action="{{ route('modulo.store') }}" method="post">
    @csrf
    @php($formIdPrefix = 'modulo-create')
    @include('modulo.form')
</form>

<form action="{{ route('modulo.update', 'test') }}" method="post">
    @method('patch')
    @csrf
    <input type="hidden" id="idEditarModulo" name="id" value="">
    @php($formIdPrefix = 'modulo-edit')
    @include('modulo.form')
</form>
```

```html
<button type="button" class="sw-bico sw-bico-edit"
        data-id="{{ $row->id }}"
        data-descripcion="{{ $row->descripcion }}"
        onclick="swEditModulo(this)">
    <i class="fa fa-edit"></i>
</button>
```

```javascript
function swEditModulo(button) {
    document.getElementById('idEditarModulo').value = button.dataset.id;
    document.getElementById('modulo-edit-descripcion').value = button.dataset.descripcion;
    swOpenModal('abrirmodalEditarModulo');
}
```

Si un valor es constante para toda la página (por ejemplo el rango de fechas del
filtro), pasarlo por Blade en un `<input type="hidden" value="{{ $x }}">` en vez
de por `data-*` + JS.

Reabrir el modal tras un error de validación de servidor:

```blade
document.addEventListener('DOMContentLoaded', function () {
    if ({{ $errors->any() ? 'true' : 'false' }}) {
        swOpenModal('{{ old('id') ? 'abrirmodalEditarModulo' : 'abrirmodalModulo' }}');
    }
});
```

---

## Reglas

- Los submenús usan `<button aria-expanded aria-controls>`; no `div onclick`.
- El menú de usuario cierra por clic externo y por `Escape`.
- En móvil, el sidebar cierra al navegar y al pulsar el backdrop.
- Si hay avatar remoto o cargado, conservar el fallback por error de imagen.
- `Escape` cierra sidebar, menú de usuario y todos los modales.
- El JS de vista va en `@push('scripts')`; las funciones compartidas van al
  fuente de `soffi-ui`, no duplicadas por vista.

---

## Funciones de alerta (nuevas en 0.3.0, venían de SoffiFac)

`soffi-ui.js` del paquete tiene **41 funciones** y varios selectores/constants
internos. La API incluye tema, layout, dropdowns, menús de fila, tablas,
modales accesibles, tabs, collapse, inputs, selects y alertas.

Las funciones de alertas son parte de esa API:

### `swInitAlerts()`

Corre sola en `DOMContentLoaded`. Por cada `.sw-alert` del documento:

- le agrega un botón `.sw-alert-close` con `aria-label="Cerrar"`
- programa el auto-cierre a `SW_ALERT_TIMEOUT` (7000 ms)
- apila los toasts con `swStackAlerts()`

No hay que llamarla a mano salvo que inyectes alertas por AJAX después de la
carga inicial.

### `swDismissAlert(alert)`

Cierra una alerta: le pone `.sw-alert-hide` (dispara la animación
`sw-alert-out`), la elimina del DOM en `animationend` y reapila el resto.
Idempotente: si ya tiene `.sw-alert-hide`, no hace nada.

### `swStackAlerts()`

Reposiciona los `.sw-alert-toast` visibles en columna desde `top: 88px`, con
12px entre cada uno. Se llama sola al iniciar y al cerrar una alerta.

```js
// Alerta desde AJAX
const el = document.createElement('div');
el.className = 'sw-alert sw-alert-success sw-alert-toast';
el.textContent = 'Guardado';
document.body.appendChild(el);
swInitAlerts();   // le engancha el cierre y la apila
```

## En React

`soffi-ui.js` manipula el DOM directo — `swOpenModal()` mueve el nodo a
`<body>` porque `.sw-main` crea un stacking context. **Eso pelea con React.**

Desde React: usar solo el CSS (`.sw-modal-backdrop.is-open`) y manejar el
estado con React. No llamar a estas funciones.

El CSS sí es directamente reutilizable: `className="sw-btn sw-btn-primary"`
funciona igual que `class=`.
