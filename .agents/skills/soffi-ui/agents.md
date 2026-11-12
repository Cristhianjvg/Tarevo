# Soffi UI — distribución para agentes

## Bundle

Copiar siempre esta carpeta completa:

```text
soffi-ui/
├── SKILL.md
├── agents.md
├── getting-started.md
├── auth.md
├── compat.md
├── components.md
├── js.md
├── laravel.md
├── layout.md
├── tokens.md
└── view-patterns.md
```

`SKILL.md` es único manifiesto. Los demás archivos son referencias enlazadas
con rutas relativas.

## Rutas

- Claude Code: `.claude/skills/soffi-ui/`.
- Codex repo-scoped: `.codex/skills/soffi-ui/`.
- OpenAI Agent Skills: `.agents/skills/soffi-ui/` y registrar el directorio
  padre como capability directory.

No mezclar esta skill con `CLAUDE.md` o `AGENTS.md`. Esos archivos definen
reglas del repositorio; esta carpeta define el procedimiento visual reusable.

## Activación

Usar `$soffi-ui` explícitamente o activarla ante tareas que mencionen Soffi UI,
clases `sw-*`, tokens, CSS, layout, Blade, formularios, tablas, modales,
dropdowns, skeletons, accesibilidad visual o migración desde Bootstrap.

No activarla para lógica de negocio, consultas DB, endpoints o estilos que no
formen parte del sistema visual.

## Flujo mínimo

1. Copiar el bundle completo en la ruta del agente (`.claude/skills/`,
   `.codex/skills/` o `.agents/skills/`).
2. Activar `$soffi-ui` y leer `SKILL.md`, `compat.md` y el capítulo del cambio.
3. Crear o refactorizar usando `sw-*`; si falta un patrón, agregarlo primero a
   `src/components/*.css` y documentarlo en `components.md`.
4. Consumir la salida publicada (`soffi-ui.css` y `soffi-ui.js`) desde Blade o
   React; no redefinir componentes en la app.
5. Ejecutar build, compatibilidad y tests antes de publicar.

El detalle completo, incluido cómo crear agentes y distribuir esta skill en
Claude Code y Codex, está en [getting-started.md](getting-started.md).

## Validación de instalación

Desde la raíz de la app consumidora:

```bash
test -f .claude/skills/soffi-ui/SKILL.md      # Claude Code
test -f .codex/skills/soffi-ui/SKILL.md       # Codex repo-scoped
test -f .agents/skills/soffi-ui/SKILL.md      # Agent Skills, si aplica
```

Validar también que los enlaces relativos de `SKILL.md` existan y que no haya
una copia parcial de la carpeta.
