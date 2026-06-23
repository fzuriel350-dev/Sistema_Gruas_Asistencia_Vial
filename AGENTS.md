# Sistema de Grúas — Contexto

## Stack
- Laravel 11 + SQLite + Vite + Alpine.js + Tailwind CSS

## Última sesión (17/06/2026)

### Objetivo
Alinear la BD con el diccionario de datos (`DICCIONARIO DE DATOS.docx`).

### Qué se implementó

**Migraciones nuevas:**
- `empresas`, `empleados`, `clientes`, `aseguradoras`, `tipos_servicio`
- `operadores`, `unidades`, `servicios_configurados`, `servicios`, `notificaciones`

**Migraciones modificadas:**
- `users` → se agregaron `empresa_id`, `empleado_id`; se eliminaron `phone`, `aseguradora`, `poliza`, `status`
- `cotizaciones` → reconstruida con FK a `clientes`, `aseguradoras`, `tipos_servicio`; se mantuvieron campos extra de vehículo y desglose de costos
- `convenios` → reconstruido con FK a `empresa`, `cliente`, `aseguradora` más `costo_banderazo`, `costo_km`, `km_incluidos`, `descuento`, `cobertura`

### Decisiones tomadas
- Se mantuvieron campos de vehículo (`marca`, `modelo`, `color`, `placas`, `no_poliza`) en `cotizaciones`
- Se mantuvo desglose de costos (`banderazo`, `costo_km`, `subtotal`, `iva`, `descuento_porcentaje`, etc.) en `cotizaciones`

### Seed data
- Admin: admin@gruas.com / password
- Cotizador: cotizador@gruas.com / password
- 1 empresa, 1 cliente, 5 aseguradoras, 3 tipos de servicio, 1 convenio

### Pendientes
- Vincular `session('empresa_id')` al usuario logueado (actualmente fallback a 1)
- CRUDs de: empleados, operadores, unidades, aseguradoras, tipos_servicio
- Vista de servicios, notificaciones
- Middleware multi-tenant
