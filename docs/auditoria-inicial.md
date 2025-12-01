# Auditoría inicial y mapa de limpieza

Este documento resume hallazgos rápidos tras una revisión exploratoria del código base para preparar un ciclo de limpieza y consolidación. Se señalan áreas con mayor impacto para ordenar archivos, rutas, migraciones y documentación.

## Avances ejecutados
- README reemplazado por una guía específica del proyecto, con prerequisitos y comandos de arranque.
- `estructura.txt` eliminado por ser un volcado de carpetas sin valor operativo.
- Rutas web refactorizadas: las closures públicas y de redirección ahora usan controladores dedicados y `routes/admin.php` quedó libre de comentarios incidentales.
- `.env.example` actualizado con nombre de aplicación, URL y disco público por defecto; README documenta credenciales seed y variables clave.
- Dashboards de Super Admin y empresa sin imports muertos y con verificación segura de la existencia del modelo `Report`.

## Documentación y entregables
- Extender el `README.md` con variables de entorno obligatorias, roles/usuarios de ejemplo y convenciones de despliegue/CI.
- Mantener un inventario reproducible de rutas y migraciones (p. ej. comandos `php artisan route:list` y `php artisan migrate:status`) en lugar de volcados manuales.

## Rutas y controladores
- Validar que la vista `welcome` siga siendo necesaria como landing pública; de lo contrario, sustituirla por una portada personalizada.
- Revisar middleware de rol/permisos en rutas de empresa y administración, y dividir `routes/admin.php` en submódulos si el tamaño crece.

## Migraciones y datos
- Las migraciones de negocio están fechadas a 2025 (`2025_10_09_012531_create_companies_table.php`, etc.) y definen tablas de compañías, empleados, roles, permisos y pivotes. Antes de consolidar, habría que confirmar el orden y dependencias (pivotes dependen de `roles`/`permissions`) y decidir si se reempaqueta en menos archivos para instalaciones limpias.
- Validar que los seeds (`database/seeders`) y factories sigan alineados con los campos definidos (e.g. `companies` tiene branding y estados; `users` incluye `company_id`, foto y estado activo).

## Vistas y assets
- Existen vistas para `admin`, `company`, componentes y plantillas Livewire en `resources/views/vendor`. Será útil mapear qué rutas las consumen y eliminar las que no estén enlazadas para reducir el footprint de build.
- Confirmar si la vista `welcome.blade.php` se usa como landing pública o puede sustituirse por una portada personalizada.

## Pruebas y CI
- Solo están los tests de ejemplo generados por Laravel (`tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`) más un test de perfil. Antes de limpiar controladores/rutas, conviene definir un set mínimo de pruebas de smoke (login, dashboards, CRUD básicos) y ajustar el pipeline CI si existe.

## Próximos pasos sugeridos
1. **Inventario de uso real**: obtener matrices ruta→controlador→vista y modelo→migración para identificar piezas huérfanas.
2. **Documentar entorno**: añadir variables `.env` esenciales y seeds de roles/usuarios al README.
3. **Depuración de rutas**: validar middleware/roles y modularizar rutas extensas.
4. **Consolidar migraciones**: revisar campos requeridos y ordenar dependencias antes de generar un dump limpio.
5. **Limpieza de vistas/assets**: cruzar importaciones de Blade/Livewire con rutas activas para eliminar archivos no usados.
6. **Plan de pruebas**: definir y automatizar pruebas básicas para proteger el refactor.
