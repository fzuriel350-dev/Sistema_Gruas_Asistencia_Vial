<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Aseguradora;
use App\Models\TipoServicio;
use App\Models\Convenio;
use App\Models\Cotizacion;
use App\Models\Operador;
use App\Models\Unidad;
use App\Models\Servicio;
use App\Models\Notificacion;
use App\Models\Oficina;
use App\Models\AutorizacionCancelacion;
use App\Models\Factura;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Empresa
        $empresa = Empresa::create([
            'nombre' => 'Grúas & Equipos',
            'color' => '#FFD500',
            'color_secundario' => '#E6A000',
            'tipografia' => 'Inter',
            'telefono_contacto' => '55 5555 1234',
            'email_contacto' => 'contacto@gruasyequipos.com',
            'whatsapp' => '525555551234',
            'direccion' => 'Av. Reforma 250, Col. Juárez, CDMX',
            'modo_oscuro' => false,
            'moneda' => '$',
            'formato_fecha' => 'd/m/Y',
            'zona_horaria' => 'America/Mexico_City',
            'idioma' => 'es',
            'footer_texto' => 'Grúas & Equipos — Confianza en el camino',
            'mostrar_precios' => true,
            'notificaciones_habilitadas' => true,
        ]);

        // 2. Oficina
        $oficina = Oficina::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Base Central',
            'direccion' => 'Av. Reforma 250, Col. Juárez, CDMX',
            'ciudad' => 'Ciudad de México',
            'estado' => 'CDMX',
            'telefono' => '55 5555 1234',
            'encargado' => 'Admin Sistema',
        ]);

        // 3. Empleados con usuarios
        $adminEmp = Empleado::create([
            'empresa_id' => $empresa->id,
            'oficina_id' => $oficina->id,
            'nombre' => 'Admin',
            'apellido_paterno' => 'Sistema',
            'apellido_materno' => '',
            'telefono' => '55 1111 0001',
            'direccion' => 'Oficinas Centrales',
            'puesto' => 'Administrador General',
            'sueldo_diario' => 800.00,
        ]);

        $adminUser = User::create([
            'empresa_id' => $empresa->id,
            'empleado_id' => $adminEmp->id,
            'name' => 'Administrador',
            'email' => 'admin@gruas.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $cotEmp = Empleado::create([
            'empresa_id' => $empresa->id,
            'oficina_id' => $oficina->id,
            'nombre' => 'Carlos',
            'apellido_paterno' => 'López',
            'apellido_materno' => 'Mendoza',
            'telefono' => '55 2222 0002',
            'direccion' => 'Av. Reforma 222, Col. Juárez',
            'puesto' => 'Cotizador',
            'sueldo_diario' => 500.00,
        ]);

        $cotUser = User::create([
            'empresa_id' => $empresa->id,
            'empleado_id' => $cotEmp->id,
            'name' => 'Carlos López',
            'email' => 'cotizador@gruas.com',
            'password' => bcrypt('password'),
            'role' => 'cotizador',
        ]);

        // Operador 1
        $op1Emp = Empleado::create([
            'empresa_id' => $empresa->id,
            'oficina_id' => $oficina->id,
            'nombre' => 'Luis',
            'apellido_paterno' => 'Hernández',
            'apellido_materno' => 'García',
            'telefono' => '55 3333 0003',
            'direccion' => 'Calle 5 de Mayo 345, Col. Centro',
            'puesto' => 'Operador de Grúa',
            'sueldo_diario' => 450.00,
        ]);

        $op1User = User::create([
            'empresa_id' => $empresa->id,
            'empleado_id' => $op1Emp->id,
            'name' => 'Luis Hernández',
            'email' => 'luis@gruas.com',
            'password' => bcrypt('password'),
            'role' => 'operador',
        ]);

        $operador1 = Operador::create([
            'empresa_id' => $empresa->id,
            'empleado_id' => $op1Emp->id,
            'licencia_tipo' => 'B',
            'licencia_año_vencimiento' => '2028-06-15',
            'licencia_vencimiento_federal' => '2028-06-15',
            'disponible' => false,
            'puntos_acumulados' => 2,
        ]);

        // Operador 2
        $op2Emp = Empleado::create([
            'empresa_id' => $empresa->id,
            'oficina_id' => $oficina->id,
            'nombre' => 'María',
            'apellido_paterno' => 'Torres',
            'apellido_materno' => 'Rivas',
            'telefono' => '55 4444 0004',
            'direccion' => 'Insurgentes Sur 567, Col. Del Valle',
            'puesto' => 'Operadora de Grúa',
            'sueldo_diario' => 450.00,
        ]);

        $op2User = User::create([
            'empresa_id' => $empresa->id,
            'empleado_id' => $op2Emp->id,
            'name' => 'María Torres',
            'email' => 'maria@gruas.com',
            'password' => bcrypt('password'),
            'role' => 'operador',
        ]);

        $operador2 = Operador::create([
            'empresa_id' => $empresa->id,
            'empleado_id' => $op2Emp->id,
            'licencia_tipo' => 'C',
            'licencia_año_vencimiento' => '2027-03-20',
            'licencia_vencimiento_federal' => '2027-03-20',
            'disponible' => true,
            'puntos_acumulados' => 0,
        ]);

        // 4. Unidades
        $unidad1 = Unidad::create([
            'empresa_id' => $empresa->id,
            'oficina_id' => $oficina->id,
            'marca' => 'Ford',
            'tipo' => 'Plataforma',
            'modelo' => 'F-550',
            'año' => 2022,
            'placas' => 'GRU-001',
            'numero_economico' => 'ECO-001',
            'numero_serie' => '1FT7X2BT6NE123456',
            'estado_emplacado' => 'CDMX',
            'seguro_vencimiento' => '2027-01-15',
            'activo' => true,
            'operador_id' => $operador1->id,
        ]);

        $unidad2 = Unidad::create([
            'empresa_id' => $empresa->id,
            'oficina_id' => $oficina->id,
            'marca' => 'Ram',
            'tipo' => 'Grúa Pluma',
            'modelo' => '5500',
            'año' => 2023,
            'placas' => 'GRU-002',
            'numero_economico' => 'ECO-002',
            'numero_serie' => '3C6UR5DL0PG789012',
            'estado_emplacado' => 'CDMX',
            'seguro_vencimiento' => '2027-06-30',
            'activo' => true,
            'operador_id' => $operador2->id,
        ]);

        $unidad3 = Unidad::create([
            'empresa_id' => $empresa->id,
            'oficina_id' => $oficina->id,
            'marca' => 'International',
            'tipo' => 'Plataforma Pesada',
            'modelo' => 'HV507',
            'año' => 2021,
            'placas' => 'GRU-003',
            'numero_economico' => 'ECO-003',
            'numero_serie' => '1HTMKAFT2MH345678',
            'estado_emplacado' => 'México',
            'seguro_vencimiento' => '2026-12-01',
            'activo' => true,
        ]);

        // 5. Aseguradoras
        $qualitas = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'Quálitas', 'telefono' => '55 1000 2001']);
        $gnp = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'GNP', 'telefono' => '55 2000 3002']);
        $axa = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'AXA', 'telefono' => '55 3000 4003']);
        $bbva = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'BBVA Seguros', 'telefono' => '55 4000 5004']);
        $mapfre = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'Mapfre', 'telefono' => '55 5000 6005']);

        // 6. Tipos de Servicio
        $arrastre = TipoServicio::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Arrastre',
            'descripcion' => 'Servicio de arrastre de vehículos ligeros y pesados',
        ]);

        $asistencia = TipoServicio::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Asistencia',
            'descripcion' => 'Asistencia vial básica: paso de corriente, cambio de llanta, etc.',
        ]);

        $plataforma = TipoServicio::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Plataforma',
            'descripcion' => 'Servicio de plataforma hidráulica para carga y descarga de vehículos',
        ]);

        $rescate = TipoServicio::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Rescate',
            'descripcion' => 'Servicio de rescate vial para vehículos varados',
        ]);

        // Asignar tipos de servicio a aseguradoras
        $qualitas->tiposServicio()->sync([$arrastre->id, $asistencia->id, $plataforma->id, $rescate->id]);
        $gnp->tiposServicio()->sync([$arrastre->id, $rescate->id]);
        $axa->tiposServicio()->sync([$asistencia->id, $plataforma->id]);
        $bbva->tiposServicio()->sync([$arrastre->id, $asistencia->id]);
        $mapfre->tiposServicio()->sync([$rescate->id, $plataforma->id]);

        // 7. Clientes
        $cliente1 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Juan Pérez',
            'empresa' => null,
            'telefono' => '55 1234 5678',
            'direccion' => 'Insurgentes Sur 123, CDMX',
            'contacto' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'aseguradora_id' => $qualitas->id,
            'numero_poliza' => 'POL-QLT-2026-100',
            'tipo_cobertura_poliza' => 'Amplia',
            'created_at' => now()->subDays(30),
            'updated_at' => now()->subDays(5),
        ]);

        $cliente2 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'María López',
            'empresa' => null,
            'telefono' => '55 9876 5432',
            'direccion' => 'Av. Universidad 456, CDMX',
            'contacto' => 'María López',
            'email' => 'maria@example.com',
            'aseguradora_id' => $axa->id,
            'numero_poliza' => 'POL-AXA-2026-050',
            'tipo_cobertura_poliza' => 'Limitada',
            'created_at' => now()->subDays(25),
            'updated_at' => now()->subDays(2),
        ]);

        $cliente3 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Roberto Díaz',
            'empresa' => 'Seguros GNP',
            'telefono' => '55 5678 9012',
            'direccion' => 'Paseo de la Reforma 789, CDMX',
            'contacto' => 'Roberto Díaz',
            'email' => 'rdiaz@gnp.com.mx',
            'aseguradora_id' => $gnp->id,
            'numero_poliza' => 'POL-GNP-2026-001',
            'tipo_cobertura_poliza' => 'Amplia',
        ]);

        $cliente4 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Ana Martínez',
            'empresa' => 'Autopistas del Valle',
            'telefono' => '55 3456 7890',
            'direccion' => 'Periférico Sur 1000, CDMX',
            'contacto' => 'Lic. Ana Martínez',
            'email' => 'amartinez@autopistas.com',
            'aseguradora_id' => $bbva->id,
            'numero_poliza' => 'POL-BBVA-2026-888',
            'tipo_cobertura_poliza' => 'Amplia',
            'created_at' => now()->subDays(20),
            'updated_at' => now()->subDay(),
        ]);

        $cliente5 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Pedro Sánchez',
            'empresa' => 'Transportes del Norte',
            'telefono' => '81 2345 6789',
            'direccion' => 'Av. Constitución 500, Monterrey',
            'contacto' => 'Pedro Sánchez',
            'email' => 'pedro@transportesnorte.com',
            'aseguradora_id' => $mapfre->id,
            'numero_poliza' => 'POL-MAP-2026-321',
            'tipo_cobertura_poliza' => 'Limitada',
            'created_at' => now()->subDays(15),
            'updated_at' => now()->subDays(6),
        ]);

        $cliente6 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Laura Castillo',
            'empresa' => 'Aseguradora Qualitas',
            'telefono' => '55 1111 2222',
            'direccion' => 'Santa Fe 300, CDMX',
            'contacto' => 'Lic. Laura Castillo',
            'email' => 'lcastillo@qualitas.com.mx',
            'aseguradora_id' => $qualitas->id,
            'numero_poliza' => 'POL-QLT-2026-045',
            'tipo_cobertura_poliza' => 'Limitada',
            'created_at' => now()->subDays(60),
            'updated_at' => now()->subDays(10),
        ]);

        $clienteUser = User::create([
            'empresa_id' => $empresa->id,
            'empleado_id' => null,
            'name' => 'Juan Pérez',
            'email' => 'cliente@gruas.com',
            'password' => bcrypt('password'),
            'role' => 'cliente',
        ]);

        // Cotizaciones de prueba para el cliente
        $cotCliente1 = Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente1->id,
            'aseguradora_id' => $qualitas->id,
            'tipo_servicio_id' => $arrastre->id,
            'folio' => 'COT-0006',
            'origen_direccion' => 'Av. Insurgentes Sur 123, CDMX',
            'destino_direccion' => 'Taller Mecánico Express, Calzada Taxqueña 450',
            'distancia_km' => 15,
            'tiempo_estimado_minutos' => 35,
            'costo_banderazo' => 450.00,
            'costo_km' => 100.00,
            'km_excedente' => 10,
            'incluye_peajes' => false,
            'costo_aprox_casetas' => 0,
            'costo_total' => 1950.00,
            'usuario_creador_id' => $clienteUser->id,
            'estatus' => 'aprobado',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDay(),
        ]);

        Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente2->id,
            'aseguradora_id' => $axa->id,
            'tipo_servicio_id' => $rescate->id,
            'folio' => 'COT-0007',
            'origen_direccion' => 'Periférico Sur 2000, CDMX',
            'destino_direccion' => 'Autopista México-Cuernavaca Km 35',
            'distancia_km' => 45,
            'tiempo_estimado_minutos' => 60,
            'costo_banderazo' => 500.00,
            'costo_km' => 90.00,
            'km_excedente' => 35,
            'incluye_peajes' => true,
            'costo_aprox_casetas' => 280.00,
            'costo_total' => 4530.00,
            'usuario_creador_id' => $clienteUser->id,
            'estatus' => 'pendiente',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente3->id,
            'aseguradora_id' => $gnp->id,
            'tipo_servicio_id' => $asistencia->id,
            'folio' => 'COT-0008',
            'origen_direccion' => 'Av. Revolución 800, CDMX',
            'destino_direccion' => 'Mismo lugar (auxilio vial)',
            'distancia_km' => 0,
            'tiempo_estimado_minutos' => 20,
            'costo_banderazo' => 350.00,
            'costo_km' => 0,
            'km_excedente' => 0,
            'incluye_peajes' => false,
            'costo_aprox_casetas' => 0,
            'costo_total' => 350.00,
            'usuario_creador_id' => $clienteUser->id,
            'estatus' => 'rechazado',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(4),
        ]);

        // Servicio de prueba para el cliente (activo)
        $servCliente = Servicio::create([
            'empresa_id' => $empresa->id,
            'cotizacion_id' => $cotCliente1->id,
            'operador_id' => $operador1->id,
            'unidad_id' => $unidad1->id,
            'oficina_id' => $oficina->id,
            'tipo_servicio_id' => 1,
            'estado' => 'en_sitio_origen',
            'fecha_inicio' => now()->setHour(9)->setMinute(0),
            'kms_salida' => 18450,
            'kms_llegada_cliente' => 18465,
            'observaciones' => 'Operador en sitio, realizando carga del vehículo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \App\Models\Notificacion::create([
            'empresa_id' => $empresa->id,
            'usuario_id' => $clienteUser->id,
            'mensaje' => 'Tu servicio #COT-0006 ha sido asignado al operador Luis Hernández.',
            'tipo' => 'servicio',
            'canal' => 'sistema_push',
            'estado' => 'no_leida',
            'created_at' => now()->subHours(3),
        ]);

        \App\Models\Notificacion::create([
            'empresa_id' => $empresa->id,
            'usuario_id' => $clienteUser->id,
            'mensaje' => 'Cotización COT-0007 está pendiente de tu revisión.',
            'tipo' => 'cotizacion',
            'canal' => 'sistema_push',
            'estado' => 'no_leida',
            'created_at' => now()->subDay(),
        ]);

        \App\Models\Notificacion::create([
            'empresa_id' => $empresa->id,
            'usuario_id' => $clienteUser->id,
            'mensaje' => 'Cotización COT-0008 fue rechazada.',
            'tipo' => 'cotizacion',
            'canal' => 'sistema_push',
            'estado' => 'leida',
            'created_at' => now()->subDays(4),
        ]);

        // 7. Convenios
        Convenio::create([
            'empresa_id' => $empresa->id,
            'aseguradora_id' => $qualitas->id,
            'tipo_servicio_id' => $arrastre->id,
            'nombre' => 'Convenio Quálitas Básico',
            'tipo' => 'local',
            'costo_banderazo' => 450.00,
            'costo_km' => 100.00,
            'km_incluidos' => 5,
            'cubre_casetas_peaje' => true,
            'descuento' => 10,
            'cobertura' => 'parcial',
        ]);

        $convenioGnp = Convenio::create([
            'empresa_id' => $empresa->id,
            'aseguradora_id' => $gnp->id,
            'tipo_servicio_id' => $rescate->id,
            'nombre' => 'Convenio GNP Corporativo',
            'tipo' => 'foraneo',
            'costo_banderazo' => 650.00,
            'costo_km' => 85.00,
            'km_incluidos' => 10,
            'cubre_casetas_peaje' => true,
            'descuento' => 15,
            'cobertura' => 'total',
        ]);

        // 8. Cotizaciones
        $cot1 = Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente1->id,
            'aseguradora_id' => $qualitas->id,
            'tipo_servicio_id' => $arrastre->id,
            'folio' => 'COT-0001',
            'origen_direccion' => 'Insurgentes Sur 123, CDMX',
            'destino_direccion' => 'Periférico 456, CDMX',
            'distancia_km' => 12,
            'tiempo_estimado_minutos' => 30,
            'costo_banderazo' => 450.00,
            'costo_km' => 100.00,
            'km_excedente' => 7,
            'incluye_peajes' => false,
            'costo_aprox_casetas' => 0,
            'costo_total' => 1650.00,
            'convenio_aplicado_id' => 1,
            'usuario_creador_id' => $cotUser->id,
            'estatus' => 'aprobado',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(4),
        ]);

        $cot2 = Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente2->id,
            'aseguradora_id' => $axa->id,
            'tipo_servicio_id' => $rescate->id,
            'folio' => 'COT-0002',
            'origen_direccion' => 'Av. Universidad 456, CDMX',
            'destino_direccion' => 'Taller Mecánico - Calzada Taxqueña',
            'distancia_km' => 8,
            'tiempo_estimado_minutos' => 20,
            'costo_banderazo' => 500.00,
            'costo_km' => 90.00,
            'km_excedente' => 3,
            'incluye_peajes' => false,
            'costo_aprox_casetas' => 0,
            'costo_total' => 1220.00,
            'usuario_creador_id' => $cotUser->id,
            'estatus' => 'pendiente',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        $cot3 = Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente3->id,
            'aseguradora_id' => $gnp->id,
            'tipo_servicio_id' => $arrastre->id,
            'folio' => 'COT-0003',
            'origen_direccion' => 'Paseo de la Reforma 789, CDMX',
            'destino_direccion' => 'Autopista México-Puebla Km 45',
            'distancia_km' => 65,
            'tiempo_estimado_minutos' => 90,
            'costo_banderazo' => 650.00,
            'costo_km' => 85.00,
            'km_excedente' => 55,
            'incluye_peajes' => true,
            'costo_aprox_casetas' => 570.00,
            'costo_total' => 6745.00,
            'convenio_aplicado_id' => $convenioGnp->id,
            'usuario_creador_id' => $adminUser->id,
            'estatus' => 'aprobado',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(1),
        ]);

        Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente4->id,
            'aseguradora_id' => $bbva->id,
            'tipo_servicio_id' => $asistencia->id,
            'folio' => 'COT-0004',
            'origen_direccion' => 'Periférico Sur 1000, CDMX',
            'destino_direccion' => 'Periférico Sur 1000, CDMX (mismo lugar)',
            'distancia_km' => 0,
            'tiempo_estimado_minutos' => 15,
            'costo_banderazo' => 350.00,
            'costo_km' => 0,
            'km_excedente' => 0,
            'incluye_peajes' => false,
            'costo_aprox_casetas' => 0,
            'costo_total' => 350.00,
            'usuario_creador_id' => $cotUser->id,
            'estatus' => 'pendiente',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente5->id,
            'aseguradora_id' => $mapfre->id,
            'tipo_servicio_id' => $rescate->id,
            'folio' => 'COT-0005',
            'origen_direccion' => 'Av. Constitución 500, Monterrey',
            'destino_direccion' => 'Autopista Monterrey-Saltillo Km 120',
            'distancia_km' => 180,
            'tiempo_estimado_minutos' => 150,
            'costo_banderazo' => 800.00,
            'costo_km' => 95.00,
            'km_excedente' => 170,
            'incluye_peajes' => true,
            'costo_aprox_casetas' => 760.00,
            'costo_total' => 18660.00,
            'usuario_creador_id' => $adminUser->id,
            'estatus' => 'rechazado',
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(6),
        ]);

        // 9. Servicios
        $servicio1 = Servicio::create([
            'empresa_id' => $empresa->id,
            'cotizacion_id' => $cot1->id,
            'operador_id' => $operador1->id,
            'unidad_id' => $unidad1->id,
            'oficina_id' => $oficina->id,
            'tipo_servicio_id' => 1,
            'estado' => 'finalizado',
            'fecha_inicio' => now()->subDays(4)->setHour(10)->setMinute(0),
            'fecha_fin' => now()->subDays(4)->setHour(10)->setMinute(45),
            'kms_salida' => 15230,
            'kms_llegada_cliente' => 15242,
            'kms_termino_servicio' => 15255,
            'kms_regreso_base' => 15270,
            'kms_cobrados_reales' => 12,
            'costo_final_real' => 1722.60,
            'observaciones' => 'Servicio completado sin contratiempos',
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(4),
        ]);

        $servicio2 = Servicio::create([
            'empresa_id' => $empresa->id,
            'cotizacion_id' => $cot3->id,
            'operador_id' => $operador1->id,
            'unidad_id' => $unidad3->id,
            'oficina_id' => $oficina->id,
            'tipo_servicio_id' => 2,
            'estado' => 'inicio_servicio',
            'fecha_inicio' => now()->setHour(9)->setMinute(30),
            'kms_salida' => 45210,
            'kms_llegada_cliente' => 45235,
            'observaciones' => 'Servicio en proceso, unidad en camino',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $servicio3 = Servicio::create([
            'empresa_id' => $empresa->id,
            'cotizacion_id' => $cot2->id,
            'operador_id' => $operador2->id,
            'unidad_id' => $unidad2->id,
            'oficina_id' => $oficina->id,
            'tipo_servicio_id' => 3,
            'estado' => 'asignado',
            'fecha_inicio' => now()->addHours(2),
            'kms_salida' => 32100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 10. Notificaciones
        $notifMsgs = [
            ['usuario_id' => $adminUser->id, 'mensaje' => 'Nuevo servicio asignado: Arrastre en Periférico Sur', 'tipo' => 'servicio'],
            ['usuario_id' => $adminUser->id, 'mensaje' => 'Cotización COT-0003 aprobada por GNP', 'tipo' => 'cotizacion'],
            ['usuario_id' => $cotUser->id, 'mensaje' => 'Cotización COT-0005 rechazada por Mapfre', 'tipo' => 'cotizacion'],
            ['usuario_id' => $op1User->id, 'mensaje' => 'Nuevo servicio asignado: Rescate en Av. Universidad', 'tipo' => 'servicio'],
            ['usuario_id' => $adminUser->id, 'mensaje' => 'El operador Luis Hernández completó un servicio', 'tipo' => 'sistema'],
            ['usuario_id' => null, 'mensaje' => 'Recordatorio: Revisar cotizaciones pendientes', 'tipo' => 'sistema'],
        ];

        foreach ($notifMsgs as $n) {
            $data = [
                'empresa_id' => $empresa->id,
                'mensaje' => $n['mensaje'],
                'canal' => 'sistema_push',
                'tipo' => $n['tipo'],
                'estado' => 'no_leida',
                'created_at' => now()->subHours(rand(1, 48)),
            ];
            if ($n['usuario_id'] !== null) {
                $data['usuario_id'] = $n['usuario_id'];
            }
            Notificacion::create($data);
        }

        // 11. Oficina — creada en sección 2

        // 12. Factura (para servicio finalizado)
        $servicioFinalizado = Servicio::where('estado', 'finalizado')->first();
        if ($servicioFinalizado) {
            Factura::create([
                'empresa_id' => $empresa->id,
                'cliente_id' => $cliente1->id,
                'servicio_id' => $servicioFinalizado->id,
                'folio_factura' => 'FAC-0001',
                'subtotal' => 1650.00,
                'iva' => 264.00,
                'total' => 1914.00,
                'estatus' => 'vigente',
            ]);
        }

        // 16. Autorizaciones Cancelación
        AutorizacionCancelacion::create([
            'servicio_id' => $servicio1->id,
            'usuario_solicitante_id' => $op1User->id,
            'motivo_cancelacion' => 'El cliente reportó que ya no requiere el servicio después de intentar contactarlo',
            'tipo_incidencia' => 'cliente_cancela',
            'estatus' => 'rechazada',
            'fecha_solicitud' => now()->subDays(3)->setHour(14)->setMinute(0),
            'fecha_resolucion' => now()->subDays(3)->setHour(16)->setMinute(30),
            'usuario_resolutor_id' => $adminUser->id,
        ]);

        AutorizacionCancelacion::create([
            'servicio_id' => $servicio3->id,
            'usuario_solicitante_id' => $op2User->id,
            'motivo_cancelacion' => 'La unidad presentó falla mecánica antes de salir a ruta',
            'tipo_incidencia' => 'falla_mecanica',
            'estatus' => 'pendiente',
            'fecha_solicitud' => now()->subHours(2),
        ]);

    }
}
