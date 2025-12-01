# Centinela360

Aplicación Laravel 10 que gestiona compañías, usuarios, empleados y clientes con paneles diferenciados según rol (Super Admin y Admin de empresa) y autenticación vía Breeze/Livewire.

## Características principales
- **Panel Super Admin**: creación y administración de empresas, activación/suspensión y gestión de usuarios globales.
- **Panel de empresa**: dashboard interno, CRUD de empleados y clientes, y vistas de programación.
- **Redirección por rol**: después de autenticarse se envía a cada usuario a su panel correspondiente.
- **Stack front-end**: Laravel Breeze con Blade + TailwindCSS + Livewire v3.

## Requisitos
- PHP 8.1+
- Extensiones de base de datos (MySQL/MariaDB o equivalente)
- Node.js 18+ y npm
- Composer

## Puesta en marcha
1. Instalar dependencias de PHP y JS:
   ```bash
   composer install
   npm install
   ```
2. Configurar variables de entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Ajusta la conexión de base de datos y el almacenamiento (`FILESYSTEM_DISK=public`).
3. Ejecutar migraciones y seeders base (roles, permisos, compañía y usuarios iniciales):
   ```bash
   php artisan migrate --seed
   ```
4. Crear el enlace de almacenamiento público:
   ```bash
   php artisan storage:link
   ```
5. Compilar assets (desarrollo):
   ```bash
   npm run dev
   # o para build de producción
   npm run build
   ```
6. Iniciar el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

## Variables de entorno clave
- `APP_NAME=Centinela360`
- `APP_URL=http://localhost:8000`
- `DB_DATABASE=centinela360`
- `DB_USERNAME` y `DB_PASSWORD` según tu instancia MySQL/MariaDB.
- `FILESYSTEM_DISK=public` para servir archivos subidos.
- Configuración SMTP (por defecto Mailpit para desarrollo).

## Rutas clave
- `/redirect`: decide el panel según el rol autenticado (Super Admin → `admin.dashboard`, Admin Empresa → `company.dashboard`, otros → `dashboard`).
- `/admin/*`: rutas protegidas por rol **Super Admin** (empresas y usuarios).
- `/company/*`: rutas protegidas por rol **Admin Empresa** (empleados, clientes, programación).

## Usuarios y roles iniciales
Los seeders crean datos mínimos para desarrollo:

- **Super Admin y Admin Empresa**: `admin@centinela360.com` / `12345678` (ambos roles asignados). Esta cuenta se asocia a la compañía semilla `Centinela Global`.
  - Roles disponibles: Super Admin, Admin Empresa, Supervisor, Guardia.
  - Permisos base: `view_dashboard`, `manage_users`, `manage_roles`, `manage_permissions`, `manage_companies`.

## Pruebas
Ejecuta la suite básica con:
```bash
php artisan test
```

## Estructura
- `app/Http/Controllers/Admin`: controladores del panel de Super Admin.
- `app/Http/Controllers/Company`: controladores del panel de empresa.
- `app/Livewire`: componentes Livewire utilizados en las vistas.
- `database/migrations`: esquema de compañías, usuarios, roles, permisos, empleados y clientes.

## Próximos pasos sugeridos
- Completar seeds/factories para crear usuarios por rol en entornos locales.
- Añadir pruebas de smoke para autenticación y flujos CRUD principales.
- Consolidar migraciones en un ciclo limpio cuando el modelo de datos se estabilice.
