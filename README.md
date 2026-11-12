# Tarevo

Aplicación web responsiva para la gestión operativa de **escenarios deportivos**. Es el proyecto de
la Actividad 1 de *Desarrollo de aplicaciones con asistentes de programación basados en IA* (UNIR).

## Qué hace la aplicación

- **Acceso** con usuario y contraseña, con dos roles: *supervisor* y *encargado operativo*.
- **Gestión de personal** (solo supervisor): registrar, editar y eliminar usuarios y asignarles un rol.
- **Gestión de escenarios deportivos** (supervisor y encargado operativo): registrar, editar y eliminar
  instalaciones con su dirección, descripción y responsable.
- **Gestión de tareas** (supervisor y encargado operativo): registrar, editar y eliminar tareas,
  asignando cada una a una instalación y a un responsable (encargado operativo).

La única restricción por rol es que la gestión de personal es exclusiva del supervisor.

## Versiones usadas

Estas son las versiones con las que se desarrolló y se comprobó que el proyecto instala y arranca.

| Componente | Versión | Notas |
|---|---|---|
| PHP | **8.5.12** | Mínimo exigido por el proyecto: 8.3 |
| Composer | **2.9.5** | Cualquier 2.x reciente |
| Laravel | **13.42.0** | Se instala solo con Composer |
| PostgreSQL | **18.6** | Base de datos de la aplicación |
| Git | **2.47.3** | Para clonar el repositorio |
| Soffi UI | **1.2.1** | Sistema de diseño; se instala con Composer desde GitHub |
| Font Awesome Free | **6.7.2** | Ya incluido en `public/vendor/fontawesome/` |
| Pest | **5.3.5** | Solo desarrollo (pruebas) |

**Node.js y npm no son necesarios** para ejecutar la aplicación: los estilos y scripts ya vienen
publicados en `public/vendor/`. (El entorno de desarrollo tenía Node 20.19.0 y npm 10.8.2, pero
no se usan.)

### Extensiones de PHP requeridas

`ctype`, `curl`, `dom`, `fileinfo`, `gd`, `json`, `mbstring`, `openssl`, `pdo_pgsql`, `pgsql`,
`tokenizer`, `xml` y `zip`. Se comprueban con:

```bash
php -m
```

## Instalación paso a paso

### 1. Instalar los requisitos

Instala **PHP 8.3 o superior** (con las extensiones de arriba), **Composer 2**, **PostgreSQL** y
**Git**. Verifica las versiones:

```bash
php -v
composer --version
psql --version
git --version
```

En Debian/Ubuntu las extensiones se instalan, por ejemplo, con
`sudo apt install php-pgsql php-mbstring php-xml php-curl php-gd php-zip` (los nombres exactos
dependen de la versión de PHP instalada).

### 2. Clonar el repositorio

```bash
git clone https://github.com/Cristhianjvg/Tarevo.git
cd Tarevo
```

### 3. Instalar las dependencias

```bash
composer install
```

Esto descarga Laravel y el paquete `soffiweb/soffi-ui` desde su repositorio de GitHub, por lo que
hace falta conexión a internet.

### 4. Crear la base de datos

Crea una base de datos vacía en PostgreSQL:

```bash
sudo -u postgres psql -c "CREATE DATABASE tarevo;"
```

Si quieres un usuario propio para la aplicación:

```bash
sudo -u postgres psql -c "CREATE USER tarevo WITH PASSWORD 'una-contraseña';"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE tarevo TO tarevo;"
sudo -u postgres psql -d tarevo -c "GRANT ALL ON SCHEMA public TO tarevo;"
```

### 5. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Abre `.env` y cambia estas líneas. **El archivo de ejemplo viene con SQLite, y este proyecto usa
PostgreSQL**, así que hay que reemplazarlas:

```env
APP_NAME=Tarevo
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tarevo
DB_USERNAME=postgres
DB_PASSWORD=la-contraseña-de-tu-usuario

# Contraseña de los usuarios de prueba (obligatoria, el seeder falla sin ella)
SEEDER_PASSWORD=elige-una-contraseña
```

Usa en `DB_USERNAME` y `DB_PASSWORD` el usuario que creaste en el paso anterior.

### 6. Crear las tablas y los usuarios de prueba

```bash
php artisan migrate --seed
```

Crea las tablas, los dos roles, un usuario por rol y los tipos de tarea (Limpieza, Mantenimiento,
Inspección y Adecuación).

### 7. Arrancar la aplicación

```bash
php artisan serve
```

Abre <http://localhost:8000> en el navegador.

## Usuarios de prueba

Los dos usuarios tienen la contraseña que pusiste en `SEEDER_PASSWORD`:

| Rol | Correo | Acceso |
|---|---|---|
| Supervisor | `supervisor@example.test` | Personal, instalaciones y tareas |
| Encargado operativo | `encargado_operativo@example.test` | Instalaciones y tareas (sin acceso a Personal) |

## Solución de problemas

| Error | Causa y solución |
|---|---|
| `could not find driver` | Falta la extensión `pdo_pgsql` de PHP. Instálala y comprueba con `php -m`. |
| `SQLSTATE[08006] ... connection refused` | PostgreSQL no está en marcha o `DB_HOST`/`DB_PORT` son incorrectos. |
| `password authentication failed` | `DB_USERNAME` o `DB_PASSWORD` no coinciden con el usuario de PostgreSQL. |
| `Define SEEDER_PASSWORD en el entorno...` | Falta `SEEDER_PASSWORD` en `.env`. Añádela y vuelve a ejecutar `php artisan migrate --seed`. |
| `No application encryption key has been specified` | Falta `php artisan key:generate`. |
| `composer install` no encuentra `soffiweb/soffi-ui` | Comprueba la conexión a internet y que GitHub sea accesible. |
| La página se ve sin estilos | Revisa que exista `public/vendor/soffi-ui/` y que `APP_URL` coincida con la dirección que abres. |

## Arquitectura

- **Backend:** Laravel 13, aplicación monolítica con patrón MVC.
- **Base de datos:** PostgreSQL.
- **Frontend:** Blade con el sistema de diseño Soffi UI y Font Awesome 6.
