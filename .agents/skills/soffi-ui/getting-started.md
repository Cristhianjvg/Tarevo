# Primeros pasos

## 1. Preparar el agente

La skill es el procedimiento visual reusable. Las políticas del proyecto viven
en `CLAUDE.md` y `AGENTS.md`; no se duplican aquí.

Distribuir toda la carpeta `skills/soffi-ui/`:

```text
.claude/skills/soffi-ui/   # Claude Code
.codex/skills/soffi-ui/    # Codex dentro del repositorio
.agents/skills/soffi-ui/   # Agent Skills compatible
```

Claude Code y Codex pueden activarla con `$soffi-ui`. También debe activarse
automáticamente ante tareas de CSS, Blade, React, tokens, clases `sw-*`, layout,
formularios, tablas, modales, accesibilidad o migración desde Bootstrap.

## 2. Usar el agente

Antes de tocar una vista:

1. Leer `SKILL.md`, `compat.md` y el capítulo específico.
2. Revisar tokens y componentes existentes.
3. Reutilizar `sw-*`; para tamaños usar `.sw-text-*`; no crear CSS local en la vista.
4. Verificar claro, oscuro, teclado, foco y responsive.
5. Ejecutar `composer check-compat`, `composer test` y `composer build`.

Para dividir trabajo entre agentes, el agente principal conserva la decisión
visual y la integración. Los agentes secundarios pueden auditar tokens, Blade,
accesibilidad o tests, pero todos reciben esta skill y `AGENTS.md` completo.

## 3. Crear un componente

1. Confirmar que no existe una familia equivalente en `components.md`.
2. Definir el contrato visual con tokens de `src/tokens/`.
3. Crear `src/components/<familia>.css`; el build lo incluye automáticamente.
4. Agregar comportamiento reusable a `js/soffi-ui.js` solo si CSS no basta.
5. Documentar markup, estados, accesibilidad y responsive en `components.md`.
6. Agregar un ejemplo a `gallery/` cuando el patrón sea demostrable.
7. Construir y publicar desde el paquete; nunca editar `dist/` manualmente.

## 4. Usar estilos en Laravel + Blade

Publicar los artefactos del paquete:

```bash
php artisan vendor:publish --tag=soffi-ui --force
```

Incluir `soffi-ui.css` después de las hojas de terceros y `soffi-ui.js` una
sola vez. En la vista, usar las clases documentadas:

```blade
<section class="sw-card">
    <h2 class="sw-card-title">Título</h2>
    <p class="sw-card-body">Contenido.</p>
</section>
```

En React se consume el mismo CSS; React administra el estado y Soffi UI solo
manipula comportamiento cuando la API JavaScript lo indica.

## 5. Definition of done

- Bundle completo instalado y activable.
- Tokens, componentes y utilidades documentados.
- Sin colores o tamaños inventados en una vista.
- Claro/oscuro, teclado y piso de navegador verificados.
- Build, compatibilidad y tests en verde.
