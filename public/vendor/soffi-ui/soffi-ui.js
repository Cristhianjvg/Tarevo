// Soffi UI — JS del sistema. Vanilla, sin jQuery ni Popper.
//
// Union de las 4 apps: base Sofficon (23 fn,
// incluye el reposicionamiento de row-menu) + las 3 de alertas de SoffiFac
// (swStackAlerts / swDismissAlert / swInitAlerts) = 26 funciones.
//
// OJO en React: estas funciones manipulan el DOM directo (swOpenModal mueve
// el nodo a <body>). Eso pelea con React. Desde React usar solo el CSS
// (.sw-modal-backdrop.is-open) y manejar el estado con React.

// Toggle tema claro/oscuro
function swToggleTheme() {
  const r = document.getElementById('root') || document.documentElement;
  r.dataset.theme = r.dataset.theme === 'dark' ? 'light' : 'dark';
  localStorage.setItem('theme', r.dataset.theme);
}

// Submenús sidebar
function swToggleSub(el) {
  const sub = el.nextElementSibling;

  el.classList.toggle('open');
  el.setAttribute('aria-expanded', el.classList.contains('open') ? 'true' : 'false');

  if (sub && sub.classList.contains('sw-sb-sub')) {
    sub.classList.toggle('open');
  }
}

// Debajo de este ancho el sidebar es overlay. Mantener en sync con el
// @media (max-width: 1199.98px) de navigation.css.
const SW_SIDEBAR_QUERY = '(max-width: 1199.98px)';

function swIsSidebarOverlay() {
  return window.matchMedia(SW_SIDEBAR_QUERY).matches;
}

const SW_SIDEBAR_COLLAPSED_KEY = 'swSidebarCollapsed';

function swSyncSidebarToggle(open) {
  const trigger = document.querySelector('.sw-sidebar-toggle');
  if (!trigger) return;
  trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
  trigger.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
}

// En escritorio el sidebar se contrae (y la elección se recuerda); por debajo
// de 1200px se abre/cierra como overlay.
function swToggleSidebar() {
  if (swIsSidebarOverlay()) {
    swSyncSidebarToggle(document.body.classList.toggle('sw-sidebar-open'));
    return;
  }

  const collapsed = document.body.classList.toggle('sw-sidebar-collapsed');
  try { localStorage.setItem(SW_SIDEBAR_COLLAPSED_KEY, collapsed ? '1' : '0'); } catch (e) {}
  swSyncSidebarToggle(!collapsed);
}

// Flecha del sidebar: contrae en escritorio, cierra el overlay en celular y
// tablet.
function swCollapseSidebar() {
  if (swIsSidebarOverlay()) {
    swCloseSidebar();
    return;
  }

  if (!document.body.classList.contains('sw-sidebar-collapsed')) {
    swToggleSidebar();
  }
}

function swRestoreSidebar() {
  let collapsed = false;
  try { collapsed = localStorage.getItem(SW_SIDEBAR_COLLAPSED_KEY) === '1'; } catch (e) {}
  document.body.classList.toggle('sw-sidebar-collapsed', collapsed);
  if (!swIsSidebarOverlay()) swSyncSidebarToggle(!collapsed);
}

function swCloseSidebar() {
  document.body.classList.remove('sw-sidebar-open');
  swSyncSidebarToggle(!swIsSidebarOverlay() && !document.body.classList.contains('sw-sidebar-collapsed'));
}

function swToggleUserMenu(el) {
  const menu = document.getElementById(el.getAttribute('aria-controls'));
  const open = menu && !menu.classList.contains('open');

  swCloseUserMenu();

  if (open) {
    menu.classList.add('open');
    el.setAttribute('aria-expanded', 'true');
  }
}

function swCloseUserMenu(restoreFocus = false) {
  const trigger = document.querySelector('.sw-user-trigger');
  const menu = document.querySelector('.sw-user-dropdown');

  if (trigger && menu && menu.classList.contains('open')) {
    menu.classList.remove('open');
    trigger.setAttribute('aria-expanded', 'false');

    if (restoreFocus) {
      trigger.focus();
    }
  }
}

// Dropdown genérico
function swToggleDropdown(trigger) {
  const menu = document.getElementById(trigger.getAttribute('aria-controls'));
  if (!menu) return;

  const open = !menu.classList.contains('open');
  swCloseDropdowns();

  if (open) {
    menu.classList.add('open');
    trigger.setAttribute('aria-expanded', 'true');
  }
}

function swCloseDropdowns(restoreFocus = false) {
  document.querySelectorAll('.sw-dropdown-menu.open').forEach(menu => {
    menu.classList.remove('open');
    const trigger = document.querySelector(`[aria-controls="${menu.id}"]`);
    if (trigger) {
      trigger.setAttribute('aria-expanded', 'false');
      if (restoreFocus) trigger.focus();
    }
  });
}

// Menu de acciones secundarias por fila de tabla ("...")
function swPositionRowMenu(menu, trigger) {
  if (!menu || !trigger) return;

  const rect = trigger.getBoundingClientRect();
  const gap = 6;
  const edge = 8;
  const menuWidth = Math.max(menu.offsetWidth, 190);
  const menuHeight = menu.offsetHeight;
  let left = rect.right - menuWidth;
  let top = rect.bottom + gap;

  if (left < edge) left = edge;
  if (left + menuWidth > window.innerWidth - edge) {
    left = Math.max(edge, window.innerWidth - menuWidth - edge);
  }

  if (top + menuHeight > window.innerHeight - edge && rect.top - menuHeight - gap >= edge) {
    top = rect.top - menuHeight - gap;
  }

  menu.style.left = `${left}px`;
  menu.style.top = `${top}px`;
}

function swRestoreRowMenu(menu) {
  const owner = menu && menu._swRowMenuOwner;

  if (!menu || !owner) return;

  owner.appendChild(menu);
  menu.classList.remove('is-portal');
  menu.style.left = '';
  menu.style.top = '';
}

function swToggleRowMenu(el) {
  const wrap = el.closest('.sw-row-menu');
  const menu = el._swRowMenuDropdown || (wrap && wrap.querySelector('.sw-row-menu-dropdown'));
  const open = menu && !menu.classList.contains('open');

  swCloseAllRowMenus();

  if (open) {
    el._swRowMenuDropdown = menu;
    menu._swRowMenuOwner = wrap;
    menu._swRowMenuTrigger = el;
    document.body.appendChild(menu);
    menu.classList.add('is-portal');
    menu.classList.add('open');
    swPositionRowMenu(menu, el);
    el.setAttribute('aria-expanded', 'true');
  }
}

function swCloseAllRowMenus() {
  document.querySelectorAll('.sw-row-menu-dropdown.open').forEach(menu => {
    menu.classList.remove('open');
    const wrap = menu._swRowMenuOwner || menu.closest('.sw-row-menu');
    const trigger = menu._swRowMenuTrigger || (wrap && wrap.querySelector('.sw-row-menu-trigger'));
    if (trigger) {
      trigger.setAttribute('aria-expanded', 'false');
    }
    swRestoreRowMenu(menu);
  });
}

function swRepositionRowMenus() {
  document.querySelectorAll('.sw-row-menu-dropdown.open.is-portal').forEach(menu => {
    swPositionRowMenu(menu, menu._swRowMenuTrigger);
  });
}

function swSyncTableStickyColumns() {
  document.querySelectorAll('.sw-list-table-wrap').forEach(wrap => {
    if (!wrap.querySelector('thead tr > .sw-table-sticky-col')) return;

    const maxScroll = wrap.scrollWidth - wrap.clientWidth;
    wrap.classList.toggle(
      'is-table-sticky-displaced',
      maxScroll > 0 && wrap.scrollLeft < maxScroll - 1,
    );
  });
}

// .sw-badge-collapsible queda solo con icono cuando la tabla, con las
// insignias completas, no cabe en el ancho visible del listado. Se mide sin
// la clase para que el colapso no se deshaga a si mismo al angostar la tabla;
// quitar y volver a poner la clase en el mismo tick no llega a pintarse.
function swSyncCollapsibleBadges() {
  document.querySelectorAll('.sw-list-table-wrap').forEach(wrap => {
    if (!wrap.querySelector('.sw-badge-collapsible')) return;
    wrap.classList.remove('is-badges-collapsed');
    wrap.classList.toggle('is-badges-collapsed', wrap.scrollWidth > wrap.clientWidth + 1);
  });
}

function swInitCollapsibleBadges() {
  swSyncCollapsibleBadges();
  if (!('ResizeObserver' in window)) return;
  const observer = new ResizeObserver(swSyncCollapsibleBadges);
  document.querySelectorAll('.sw-list-table-wrap').forEach(wrap => observer.observe(wrap));
}

function swInitTableStickyColumns() {
  document.querySelectorAll('.sw-list-table-wrap').forEach(wrap => {
    if (wrap.querySelector('thead tr > .sw-table-sticky-col')) {
      wrap.addEventListener('scroll', swSyncTableStickyColumns, { passive: true });
    }
  });

  swSyncTableStickyColumns();
}

function swToggleTableRow(trigger) {
  const target = document.querySelector(trigger.dataset.target);
  if (!target) return;

  const willOpen = trigger.getAttribute('aria-expanded') !== 'true';
  const table = trigger.closest('table');

  table?.querySelectorAll('.sw-table-expand[aria-expanded="true"]').forEach(other => {
    if (other === trigger) return;
    other.setAttribute('aria-expanded', 'false');
    const otherTarget = document.querySelector(other.dataset.target);
    if (otherTarget) otherTarget.hidden = true;
  });

  trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
  target.hidden = !willOpen;
}

function swSyncTableSelection(table) {
  const master = table.querySelector('.sw-table-select-all');
  const rows = [...table.querySelectorAll('tbody .sw-checkbox-input')];
  if (!master || !rows.length) return;

  const selected = rows.filter(checkbox => checkbox.checked).length;
  master.checked = selected === rows.length;
  master.indeterminate = selected > 0 && selected < rows.length;
  master.setAttribute('aria-checked', master.indeterminate ? 'mixed' : String(master.checked));
}

function swToggleTableSelection(master) {
  const table = master.closest('table');
  if (!table) return;

  table.querySelectorAll('tbody .sw-checkbox-input').forEach(checkbox => {
    checkbox.checked = master.checked;
  });
  swSyncTableSelection(table);
}

function swSyncSelectColor(select) {
  select.classList.toggle('sw-select-empty', select.value === '');
}

function swSyncAuthInputGroup(group) {
  group.classList.toggle('sw-error', Boolean(group.querySelector('.sw-error')));
}

document.addEventListener('click', e => {
  const trigger = e.target.closest && e.target.closest('.sw-table-expand');
  if (trigger) swToggleTableRow(trigger);
});

document.addEventListener('change', e => {
  if (e.target.matches && e.target.matches('.sw-table-select-all')) {
    swToggleTableSelection(e.target);
  } else if (e.target.matches && e.target.matches('.sw-list-table-wrap tbody .sw-checkbox-input')) {
    swSyncTableSelection(e.target.closest('table'));
  } else if (e.target.matches && e.target.matches('.sw-select')) {
    swSyncSelectColor(e.target);
  }
});

// Select buscador — abrir/cerrar dropdown
function swToggleSS(id) {
  const w = document.getElementById(id);
  const t = w.querySelector('.sw-ss-trigger');
  const d = w.querySelector('.sw-ss-dropdown');
  const open = d.classList.toggle('open');
  t.classList.toggle('open', open);
  if (open) {
    setTimeout(() => w.querySelector('.sw-ss-search')?.focus(), 40);
  }
}

// Select buscador — seleccionar opción
function swSelectOpt(id, opt) {
  const w = document.getElementById(id);
  w.querySelectorAll('.sw-ss-opt').forEach(o => o.classList.remove('selected'));
  opt.classList.add('selected');
  const tv = w.querySelector('.sw-ss-trigger-val');
  tv.textContent = opt.dataset.val || opt.textContent.trim();
  tv.classList.remove('placeholder');
  swToggleSS(id);
}

// Select buscador — filtrar opciones
function swFilterSS(id, q) {
  const list = document.getElementById(id + '-list');
  let found = 0;
  list.querySelectorAll('.sw-ss-opt').forEach(o => {
    const match = o.textContent.toLowerCase().includes(q.toLowerCase());
    o.style.display = match ? '' : 'none';
    if (match) found++;
  });
  let empty = list.querySelector('.sw-ss-empty');
  if (!found) {
    if (!empty) {
      empty = document.createElement('div');
      empty.className = 'sw-ss-empty';
      empty.textContent = 'Sin resultados';
      list.appendChild(empty);
    }
    empty.style.display = '';
  } else if (empty) {
    empty.style.display = 'none';
  }
}

const SW_MODAL_FOCUSABLE_SELECTOR = [
  'a[href]',
  'button:not([disabled])',
  'input:not([disabled]):not([type="hidden"])',
  'select:not([disabled])',
  'textarea:not([disabled])',
  '[tabindex]:not([tabindex="-1"])',
].join(',');

function swGetOpenModal() {
  const open = document.querySelectorAll('.sw-modal-backdrop.is-open');
  return open.length ? open[open.length - 1] : null;
}

function swModalAllowsBackdrop(backdrop) {
  return backdrop.dataset.modalBackdrop !== 'static'
    && backdrop.dataset.modalBackdrop !== 'false';
}

function swModalAllowsEscape(backdrop) {
  return backdrop.dataset.modalEscape !== 'false';
}

function swModalFocusable(dialog) {
  return [...dialog.querySelectorAll(SW_MODAL_FOCUSABLE_SELECTOR)]
    .filter(el => el.offsetWidth || el.offsetHeight || el.getClientRects().length);
}

function swFocusModal(dialog) {
  if (!dialog) return;
  const preferred = dialog.querySelector(
    '[autofocus], input:not([type="hidden"]), select, textarea',
  );
  const focusable = swModalFocusable(dialog);
  (preferred || focusable[0] || dialog).focus();
}

function swTrapModalFocus(event) {
  const backdrop = swGetOpenModal();
  const dialog = backdrop?.querySelector('.sw-modal');
  if (!dialog) return;

  const focusable = swModalFocusable(dialog);
  if (!focusable.length) {
    event.preventDefault();
    dialog.focus();
    return;
  }

  if (!dialog.contains(document.activeElement)) {
    event.preventDefault();
    focusable[0].focus();
    return;
  }

  const first = focusable[0];
  const last = focusable[focusable.length - 1];
  if (event.shiftKey && document.activeElement === first) {
    event.preventDefault();
    last.focus();
  } else if (!event.shiftKey && document.activeElement === last) {
    event.preventDefault();
    first.focus();
  }
}

// Modal nativo — abrir/cerrar
function swOpenModal(id) {
  const el = document.getElementById(id);
  if (!el) return;
  if (!el.classList.contains('is-open')) {
    el._swModalReturnFocus = document.activeElement;
  }
  // .sw-main.sw-fade-in anima opacity y crea su propio stacking context:
  // un modal position:fixed anidado ahi queda atrapado debajo del topbar
  // sin importar su z-index. Se reubica como hijo directo de <body>.
  if (el.parentNode !== document.body) {
    document.body.appendChild(el);
  }
  el.classList.add('is-open');
  el.setAttribute('aria-hidden', 'false');
  document.body.classList.add('sw-modal-open');
  const dialog = el.querySelector('.sw-modal');
  if (dialog && !dialog.hasAttribute('tabindex')) dialog.setAttribute('tabindex', '-1');
  // Los valores de edicion se asignan por JS (input.value = ...) justo antes de este
  // llamado y eso no dispara 'input', asi que el color mm/dd/yyyy quedaria desactualizado.
  el.querySelectorAll(SW_DATE_INPUT_SELECTOR).forEach(swSyncDateInputColor);
  requestAnimationFrame(() => {
    if (el.classList.contains('is-open')) swFocusModal(dialog);
  });
}

function swCloseModal(id) {
  const el = document.getElementById(id);
  if (!el) return;
  const returnFocus = el._swModalReturnFocus;
  el.classList.remove('is-open');
  el.setAttribute('aria-hidden', 'true');
  if (!document.querySelector('.sw-modal-backdrop.is-open')) {
    document.body.classList.remove('sw-modal-open');
  }
  el._swModalReturnFocus = null;
  if (returnFocus && returnFocus.isConnected && typeof returnFocus.focus === 'function') {
    returnFocus.focus();
  }
}

function swCloseAllModals() {
  const open = [...document.querySelectorAll('.sw-modal-backdrop.is-open')];
  const returnFocus = open.length ? open[open.length - 1]._swModalReturnFocus : null;
  open.forEach(el => {
    el.classList.remove('is-open');
    el.setAttribute('aria-hidden', 'true');
    el._swModalReturnFocus = null;
  });
  document.body.classList.remove('sw-modal-open');
  if (returnFocus && returnFocus.isConnected && typeof returnFocus.focus === 'function') {
    returnFocus.focus();
  }
}

// Tabs propias (reemplazan data-toggle="tab" de Bootstrap, que ya no cargamos)
function swActivateTab(event, link) {
  event.preventDefault();

  const targetId = link.getAttribute('href').slice(1);
  const target = document.getElementById(targetId);
  if (!target) return;

  const tabList = link.closest('[role="tablist"]');
  if (tabList) {
    tabList.querySelectorAll('.nav-link').forEach(a => {
      a.classList.remove('active');
      a.setAttribute('aria-selected', 'false');
    });
  }
  link.classList.add('active');
  link.setAttribute('aria-selected', 'true');

  const tabContent = target.closest('.tab-content');
  if (tabContent) {
    tabContent.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
  }
  target.classList.add('active');
}

// Collapse propio (reemplaza data-toggle="collapse" de Bootstrap, que ya no
// cargamos). Respeta data-parent para que funcione como acordeon: al abrir uno
// se cierran los hermanos dentro de ese contenedor.
function swToggleCollapse(trigger) {
  const sel = trigger.dataset.target || trigger.getAttribute('href');
  const target = sel && document.querySelector(sel);
  if (!target) return;

  const willOpen = !target.classList.contains('show');

  const parentSel = target.dataset.parent;
  if (parentSel && willOpen) {
    const parent = document.querySelector(parentSel);
    if (parent) {
      parent.querySelectorAll('.collapse.show').forEach(other => {
        if (other === target) return;
        other.classList.remove('show');
        parent
          .querySelectorAll('[data-toggle="collapse"][aria-expanded="true"]')
          .forEach(t => {
            const s = t.dataset.target || t.getAttribute('href');
            if (s && document.querySelector(s) === other) {
              t.setAttribute('aria-expanded', 'false');
            }
          });
      });
    }
  }

  target.classList.toggle('show', willOpen);
  trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
}

// [data-sw-collapse] hace lo mismo que [data-toggle="collapse"] pero sin chocar
// con el data-api de Bootstrap en las apps que aun cargan su JS: con ambos
// atributos iguales, Bootstrap y esta libreria alternaban el panel a la vez y
// no cerraba.
document.addEventListener('click', e => {
  const trigger = e.target.closest && e.target.closest('[data-toggle="collapse"], [data-sw-collapse]');
  if (trigger) {
    e.preventDefault();
    swToggleCollapse(trigger);
  }
});

// Input de archivo — muestra el nombre elegido en el widget propio (label + input oculto)
function swFileInputName(input) {
  const nameEl = input.closest('.sw-file-input')?.querySelector('.sw-file-input-name');
  if (!nameEl) return;
  const hasFile = input.files && input.files.length > 0;
  nameEl.textContent = hasFile ? input.files[0].name : nameEl.dataset.default;
  nameEl.classList.toggle('has-file', hasFile);
}

// Inputs date/time/datetime-local — colorea mm/dd/yyyy como placeholder mientras esten vacios
const SW_DATE_INPUT_SELECTOR = 'input[type="date"], input[type="time"], input[type="datetime-local"]';

function swSyncDateInputColor(input) {
  input.classList.toggle('sw-date-empty', !input.value);
}

document.addEventListener('input', e => {
  if (e.target.matches && e.target.matches(SW_DATE_INPUT_SELECTOR)) {
    swSyncDateInputColor(e.target);
  }
});

// Cerrar dropdown al hacer clic afuera
document.addEventListener('click', e => {
  document.querySelectorAll('.sw-ss-wrap').forEach(w => {
    if (!w.contains(e.target)) {
      w.querySelector('.sw-ss-trigger')?.classList.remove('open');
      w.querySelector('.sw-ss-dropdown')?.classList.remove('open');
    }
  });

  if (!e.target.closest('.sw-user-menu')) {
    swCloseUserMenu();
  }

  if (!e.target.closest('.sw-dropdown')) {
    swCloseDropdowns();
  }

  if (!e.target.closest('.sw-row-menu') && !e.target.closest('.sw-row-menu-dropdown')) {
    swCloseAllRowMenus();
  }

  if (e.target.classList.contains('sw-modal-backdrop') && swModalAllowsBackdrop(e.target)) {
    swCloseModal(e.target.id);
  }
});

// Alertas flash (.sw-alert): desaparecen solas y el usuario tambien puede
// cerrarlas a mano. Las .sw-alert-toast ademas flotan sobre el contenido y
// se apilan si hay varias; las .sw-alert normales se quedan en el flujo de
// la pagina (ver .sw-alert-toast en components.css).
const SW_ALERT_TIMEOUT = 7000;

function swStackAlerts() {
  let offset = 88;
  document.querySelectorAll('.sw-alert-toast:not(.sw-alert-hide)').forEach(alert => {
    alert.style.top = offset + 'px';
    offset += alert.offsetHeight + 12;
  });
}

function swDismissAlert(alert) {
  if (!alert || alert.classList.contains('sw-alert-hide')) return;
  alert.classList.add('sw-alert-hide');
  alert.addEventListener('animationend', () => {
    alert.remove();
    swStackAlerts();
  }, { once: true });
}

function swInitAlerts() {
  const alerts = document.querySelectorAll('.sw-alert');
  alerts.forEach(alert => {
    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'sw-alert-close';
    closeBtn.setAttribute('aria-label', 'Cerrar');
    closeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
    closeBtn.addEventListener('click', () => swDismissAlert(alert));
    alert.appendChild(closeBtn);

    setTimeout(() => swDismissAlert(alert), SW_ALERT_TIMEOUT);
  });
  swStackAlerts();
}

// Restaurar tema guardado
document.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    const root = document.getElementById('root') || document.documentElement;
    root.dataset.theme = savedTheme;
  }

  document.querySelectorAll('.sw-user-avatar-img').forEach(img => {
    const useFallback = () => img.parentElement.classList.add('is-fallback');

    img.addEventListener('error', useFallback);

    if (img.complete && img.naturalWidth === 0) {
      useFallback();
    }
  });

  document.querySelectorAll(SW_DATE_INPUT_SELECTOR).forEach(swSyncDateInputColor);
  document.querySelectorAll('.sw-select').forEach(swSyncSelectColor);
  document.querySelectorAll('.sw-auth-input-group').forEach(swSyncAuthInputGroup);

  swRestoreSidebar();
  swInitAlerts();
  swInitTableStickyColumns();
  swInitCollapsibleBadges();

  document.querySelectorAll('.sw-sidebar a').forEach(link => {
    link.addEventListener('click', () => {
      if (swIsSidebarOverlay()) {
        swCloseSidebar();
      }
    });
  });

  if (window.jQuery) {
    window.jQuery(document).on('show.bs.modal', '.modal', function () {
      if (this.parentNode !== document.body) {
        document.body.appendChild(this);
      }
    });
  }
});

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    swCloseSidebar();
    swCloseUserMenu(true);
    swCloseDropdowns(true);
    swCloseAllRowMenus();
    const modal = swGetOpenModal();
    if (modal && swModalAllowsEscape(modal)) {
      e.preventDefault();
      swCloseModal(modal.id);
    }
  } else if (e.key === 'Tab') {
    swTrapModalFocus(e);
  }
});

window.addEventListener('resize', () => {
  swRepositionRowMenus();
  swSyncTableStickyColumns();
  swSyncCollapsibleBadges();
  if (swIsSidebarOverlay()) {
    swSyncSidebarToggle(document.body.classList.contains('sw-sidebar-open'));
  } else {
    document.body.classList.remove('sw-sidebar-open');
    swSyncSidebarToggle(!document.body.classList.contains('sw-sidebar-collapsed'));
  }
});

window.addEventListener('scroll', swRepositionRowMenus, true);

// Validacion de formularios (.needs-validation) — reemplaza el patron
// Bootstrap "checkValidity() + was-validated", que dependia del CSS de
// Bootstrap (inexistente aqui) para pintar los campos invalidos. Pinta
// sw-error/sw-hint-error, igual que los errores de validacion del
// servidor via @error() en Blade.
// field.validationMessage viene en el idioma del navegador/SO, no en el
// de la pagina, asi que se arma el texto en español segun el tipo de
// error en vez de usar el mensaje nativo.
function swValidationMessage(field) {
  const v = field.validity;
  if (v.valueMissing) return 'Este campo es obligatorio.';
  if (v.typeMismatch) return 'El valor no tiene el formato esperado.';
  if (v.patternMismatch) return field.title || 'El formato no es valido.';
  if (v.tooShort) return `Debe tener al menos ${field.minLength} caracteres.`;
  if (v.tooLong) return `Debe tener como maximo ${field.maxLength} caracteres.`;
  if (v.rangeUnderflow) return `El valor minimo es ${field.min}.`;
  if (v.rangeOverflow) return `El valor maximo es ${field.max}.`;
  if (v.stepMismatch) return 'El valor ingresado no es valido para este campo.';
  if (v.badInput) return 'El valor ingresado no es valido.';
  return field.validationMessage || 'El valor no es valido.';
}

function swValidateField(field) {
  const valid = field.checkValidity();
  field.classList.toggle('sw-error', !valid);

  const authGroup = field.closest('.sw-auth-input-group');
  if (authGroup) swSyncAuthInputGroup(authGroup);

  let hint = field.nextElementSibling;
  if (!hint || !hint.classList.contains('sw-hint-error')) {
    hint = document.createElement('small');
    hint.className = 'sw-hint-error';
    field.insertAdjacentElement('afterend', hint);
  }
  hint.textContent = valid ? '' : swValidationMessage(field);

  return valid;
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.needs-validation').forEach(form => {
    const fields = form.querySelectorAll('.sw-input, .sw-select, .sw-textarea');

    form.addEventListener('submit', event => {
      let formValid = true;
      fields.forEach(field => {
        if (!swValidateField(field)) {
          formValid = false;
        }
      });

      if (!formValid) {
        event.preventDefault();
        event.stopPropagation();
      }
    });
  });
});

// Acciones de cabecera con desborde: en celular y tablet vertical, los botones
// que pasan del limite de `data-sw-overflow="N"` se mueven al menu "Mas
// acciones" (se mueven, no se clonan: conservan sus onclick). En pantallas
// grandes vuelven a su lugar. Sin el atributo no cambia nada.
const SW_OVERFLOW_QUERY = '(max-width: 991.98px)';
let swOverflowSeq = 0;

function swOverflowCollapse(box) {
  if (box._swOverflowState) return;

  const limit = parseInt(box.dataset.swOverflow, 10);
  if (!limit || limit < 2) return;

  const buttons = Array.from(box.children).filter(el => el.classList.contains('sw-btn'));
  let wrap = Array.from(box.children).find(el => el.classList.contains('sw-dropdown'));

  if (buttons.length + (wrap ? 1 : 0) <= limit) return;

  // El boton principal nunca se mueve: el resto se reparte por orden en los
  // espacios que sobran y lo demas va al menu.
  const others = buttons.filter(el => !el.classList.contains('sw-btn-primary'));
  const kept = Math.max(0, limit - 1 - (buttons.length - others.length));
  const moved = others.slice(kept);
  if (!moved.length) return;

  const created = !wrap;
  let divider = null;
  let menu;

  if (created) {
    const id = `swOverflowMenu${++swOverflowSeq}`;
    // Mismo aspecto (variante y tamaño) que los botones que se esconden.
    const triggerClass = moved[0].className.split(/\s+/).filter(c => c.startsWith('sw-btn')).join(' ');
    wrap = document.createElement('div');
    wrap.className = 'sw-dropdown';
    wrap.innerHTML =
      `<button type="button" class="${triggerClass}" aria-haspopup="menu" aria-expanded="false" aria-controls="${id}" onclick="swToggleDropdown(this)">` +
      'Más acciones <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button>' +
      `<div class="sw-dropdown-menu" id="${id}" role="menu"></div>`;
    box.appendChild(wrap);
    menu = wrap.querySelector('.sw-dropdown-menu');
  } else {
    menu = wrap.querySelector('.sw-dropdown-menu');
    if (!menu) return;
    if (menu.children.length) {
      divider = document.createElement('div');
      divider.className = 'sw-dropdown-divider';
      divider.setAttribute('role', 'separator');
    }
  }

  const entries = moved.map(el => {
    const placeholder = document.createComment('sw-overflow');
    el.replaceWith(placeholder);
    const entry = { el, placeholder, className: el.className, role: el.getAttribute('role') };
    el.className = 'sw-dropdown-item';
    el.setAttribute('role', 'menuitem');
    el.addEventListener('click', swOverflowItemClick);
    return entry;
  });

  const first = menu.firstChild;
  entries.forEach(entry => menu.insertBefore(entry.el, first));
  if (divider) menu.insertBefore(divider, first);

  box._swOverflowState = { entries, wrap, created, divider };
}

function swOverflowItemClick() {
  swCloseDropdowns();
}

function swOverflowRestore(box) {
  const state = box._swOverflowState;
  if (!state) return;

  swCloseDropdowns();

  state.entries.forEach(({ el, placeholder, className, role }) => {
    el.removeEventListener('click', swOverflowItemClick);
    el.className = className;
    if (role === null) el.removeAttribute('role'); else el.setAttribute('role', role);
    placeholder.replaceWith(el);
  });

  if (state.divider) state.divider.remove();
  if (state.created) state.wrap.remove();

  box._swOverflowState = null;
}

function swSyncOverflow(small) {
  document.querySelectorAll('[data-sw-overflow]').forEach(box => {
    if (small) swOverflowCollapse(box); else swOverflowRestore(box);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const query = window.matchMedia(SW_OVERFLOW_QUERY);
  swSyncOverflow(query.matches);

  if (query.addEventListener) {
    query.addEventListener('change', event => swSyncOverflow(event.matches));
  } else {
    query.addListener(event => swSyncOverflow(event.matches));
  }
});
