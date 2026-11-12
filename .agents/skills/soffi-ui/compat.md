# Compatibilidad — la regla que no se negocia

Soffi UI corre en computadoras antiguas. Esto no es una preferencia estética:
es el requisito que define qué CSS se puede escribir.

## El piso

| Navegador | Versión mínima | Por qué |
|---|---|---|
| Chrome / Edge | **109** | Última versión que corre en Windows 7 / 8 / 8.1 y Server 2012. Congeladas ahí desde enero 2023, sin más actualizaciones. |
| Firefox | **115 ESR** | Última para Win 7/8.1 y macOS 10.12–10.14. |
| Safari | **15.6** | macOS antiguos que no pueden subir. |

## El número que explica todo

`color-mix()` y `oklch()` llegaron en **Chrome 111**. El techo del parque
antiguo es **Chrome 109**. Quedan **2 versiones** por encima.

Por eso el bug que ya se arregló: no fue mala suerte, fue usar features que el
parque real no soporta.

## Regla 1 — `color-mix()` siempre con fallback estático

**Patrón de doble declaración.** El valor estático primero, el moderno después.
El navegador viejo descarta la línea que no entiende y se queda con la primera;
el moderno aplica la segunda y gana por orden de cascada.

```css
/* CORRECTO */
.sw-alert-warning {
    background: rgba(250, 151, 0, 0.12);
    background: color-mix(in srgb, var(--wa) 12%, transparent);
    border: 1px solid rgba(250, 151, 0, 0.26);
    border: 1px solid color-mix(in srgb, var(--wa) 26%, transparent);
}

/* INCORRECTO — en Chrome 109 este alert queda sin fondo ni borde */
.sw-alert-warning {
    background: color-mix(in srgb, var(--wa) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--wa) 26%, transparent);
}
```

### Cómo calcular el valor estático

Mezcla con `transparent` → `rgba()` del color base con ese alpha:

| Moderno | Estático |
|---|---|
| `color-mix(in srgb, var(--p) 12%, transparent)` | `rgba(40, 68, 154, 0.12)` |
| `color-mix(in srgb, var(--wa) 30%, transparent)` | `rgba(250, 151, 0, 0.3)` |
| `color-mix(in srgb, var(--su) 40%, transparent)` | `rgba(0, 186, 123, 0.4)` |

Mezcla con `var(--b1)` (fondo) o `#000` → hex del resultado en **tema claro**:

| Moderno | Estático |
|---|---|
| `color-mix(in srgb, var(--p) 6%, var(--b1))` | `#f0f2f9` |
| `color-mix(in srgb, var(--p) 12%, var(--b1))` | `#e3e7f3` |
| `color-mix(in srgb, var(--wa) 50%, #000)` | `#7d4c00` |

> El fallback de una mezcla con `var(--b1)` es una aproximación del tema claro.
> En oscuro sin soporte de `color-mix()` queda algo corrido pero legible — es
> el criterio que ya venía el sistema, no se cambia.

Valores RGB de la paleta, para calcular:
`--p #28449a` = 40,68,154 · `--s #4361ee` = 67,97,238 · `--a #fa75ad` = 250,117,173 ·
`--su #00ba7b` = 0,186,123 · `--wa #fa9700` = 250,151,0 · `--er #e11d48` = 225,29,72 ·
`--in #00a4f2` = 0,164,242 · `--b1 #fdfdff` = 253,253,255

## Regla 2 — dentro de `:root` va `@supports`, no doble declaración

Una custom property con valor inválido **no se descarta**: se hereda como
`unset`. La doble declaración no funciona para tokens.

```css
/* CORRECTO */
:root {
    --sw-border: 1px solid rgba(67, 97, 238, 0.28);
}

@supports (background: color-mix(in srgb, red 1%, blue)) {
    :root {
        --sw-border: 1px solid color-mix(in srgb, var(--sw-secondary) 28%, transparent);
    }
}
```

## Regla 3 — `oklch()` está prohibido

No tiene forma de degradar dentro de una custom property. Usar hex.

```css
/* INCORRECTO */  --sw-success: oklch(69% 0.17 162.48);
/* CORRECTO   */  --sw-success: #00ba7b;
```

## Features permitidas y prohibidas

| Feature | Requiere | ¿Se puede? |
|---|---|---|
| `var(--…)` | Chr 49 | ✅ es el cimiento del sistema |
| `display: grid` | Chr 57 | ✅ |
| `position: sticky` | Chr 56 | ✅ |
| `min()` / `max()` | Chr 79 | ✅ |
| `color-scheme` | Chr 81 | ✅ |
| `gap` en flex | Chr 84 | ✅ |
| `inset:` | Chr 87 | ✅ |
| `accent-color` | Chr 93 | ✅ degrada a checkbox azul del navegador |
| `:has()` | Chr 105 / **FF 121** | ⚠️ solo para detalles cosméticos — **falla en Firefox 115 ESR** |
| `color-mix()` | Chr 111 | ⚠️ solo con fallback (regla 1 y 2) |
| `oklch()` | Chr 111 | ❌ |
| `light-dark()` | Chr 123 | ❌ |
| `@container` | Chr 105 | ❌ |
| `text-wrap: balance` | Chr 114 | ❌ |
| anidamiento CSS nativo | Chr 112 | ❌ |

## El check

No es honor system. `scripts/check-compat.php` **falla el build** si algo viola
las reglas 1–3:

```bash
php scripts/check-compat.php
php scripts/build.php      # corre el check antes de escribir dist/
```

Salida esperada:

```
archivos: 30 · color-mix(): 118 (116 con fallback, 2 dentro de @supports)
OK — sin violaciones de compatibilidad.
```

Si agregás CSS con `color-mix()` sin fallback, el build no pasa. Es a propósito:
la convención escrita ya falló una vez (SoffiFac y gesnom `dev` acumularon 204
declaraciones sin fallback), así que ahora es un check.
