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

        // 2. Empleados con usuarios
        $adminEmp = Empleado::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Admin',
            'apellido_paterno' => 'Sistema',
            'apellido_materno' => '',
            'telefono' => '55 1111 0001',
            'direccion' => 'Oficinas Centrales',
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
            'nombre' => 'Carlos',
            'apellido_paterno' => 'López',
            'apellido_materno' => 'Mendoza',
            'telefono' => '55 2222 0002',
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
            'nombre' => 'Luis',
            'apellido_paterno' => 'Hernández',
            'apellido_materno' => 'García',
            'telefono' => '55 3333 0003',
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
            'disponible' => false,
        ]);

        // Operador 2
        $op2Emp = Empleado::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'María',
            'apellido_paterno' => 'Torres',
            'apellido_materno' => 'Rivas',
            'telefono' => '55 4444 0004',
        ]);

        User::create([
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
            'disponible' => true,
        ]);

        // 3. Unidades
        $unidad1 = Unidad::create([
            'empresa_id' => $empresa->id,
            'marca' => 'Ford',
            'tipo' => 'Plataforma',
            'año' => 2022,
            'placas' => 'GRU-001',
            'numero_serie' => '1FT7X2BT6NE123456',
            'seguro_vencimiento' => '2027-01-15',
            'operador_id' => $operador1->id,
        ]);

        $unidad2 = Unidad::create([
            'empresa_id' => $empresa->id,
            'marca' => 'Ram',
            'tipo' => 'Grúa Pluma',
            'año' => 2023,
            'placas' => 'GRU-002',
            'numero_serie' => '3C6UR5DL0PG789012',
            'seguro_vencimiento' => '2027-06-30',
            'operador_id' => $operador2->id,
        ]);

        $unidad3 = Unidad::create([
            'empresa_id' => $empresa->id,
            'marca' => 'International',
            'tipo' => 'Plataforma Pesada',
            'año' => 2021,
            'placas' => 'GRU-003',
            'numero_serie' => '1HTMKAFT2MH345678',
            'seguro_vencimiento' => '2026-12-01',
        ]);

        // 4. Clientes
        $cliente1 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Juan Pérez',
            'empresa' => null,
            'telefono' => '55 1234 5678',
            'direccion' => 'Insurgentes Sur 123, CDMX',
            'contacto' => 'Juan Pérez',
        ]);

        $cliente2 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'María López',
            'empresa' => null,
            'telefono' => '55 9876 5432',
            'direccion' => 'Av. Universidad 456, CDMX',
            'contacto' => 'María López',
        ]);

        $cliente3 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Roberto Díaz',
            'empresa' => 'Seguros GNP',
            'telefono' => '55 5678 9012',
            'direccion' => 'Paseo de la Reforma 789, CDMX',
            'contacto' => 'Roberto Díaz',
        ]);

        $cliente4 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Ana Martínez',
            'empresa' => 'Autopistas del Valle',
            'telefono' => '55 3456 7890',
            'direccion' => 'Periférico Sur 1000, CDMX',
            'contacto' => 'Lic. Ana Martínez',
        ]);

        $cliente5 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Pedro Sánchez',
            'empresa' => 'Transportes del Norte',
            'telefono' => '81 2345 6789',
            'direccion' => 'Av. Constitución 500, Monterrey',
            'contacto' => 'Pedro Sánchez',
        ]);

        $cliente6 = Cliente::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Laura Castillo',
            'empresa' => 'Aseguradora Qualitas',
            'telefono' => '55 1111 2222',
            'direccion' => 'Santa Fe 300, CDMX',
            'contacto' => 'Lic. Laura Castillo',
        ]);

        // 5. Aseguradoras
        $qualitas = Aseguradora::where('empresa_id', $empresa->id)->where('nombre', 'Quálitas')->first()
            ?? Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'Quálitas']);

        $gnp = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'GNP']);
        $axa = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'AXA']);
        $bbva = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'BBVA Seguros']);
        $mapfre = Aseguradora::create(['empresa_id' => $empresa->id, 'nombre' => 'Mapfre']);

        // 6. Tipos de Servicio
        $arrastre = TipoServicio::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Arrastre',
            'descripcion' => 'Servicio de arrastre de vehículos ligeros y pesados',
        ]);

        $rescate = TipoServicio::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Rescate',
            'descripcion' => 'Servicio de rescate vial para vehículos varados',
        ]);

        $auxilio = TipoServicio::create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Auxilio Vial',
            'descripcion' => 'Asistencia vial básica: paso de corriente, cambio de llanta, etc.',
        ]);

        // 7. Convenios
        Convenio::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente6->id,
            'aseguradora_id' => $qualitas->id,
            'nombre' => 'Convenio Quálitas Básico',
            'tipo' => 'local',
            'costo_banderazo' => 450.00,
            'costo_km' => 100.00,
            'km_incluidos' => 5,
            'descuento' => 10,
            'cobertura' => 'parcial',
        ]);

        $convenioGnp = Convenio::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente3->id,
            'aseguradora_id' => $gnp->id,
            'nombre' => 'Convenio GNP Corporativo',
            'tipo' => 'foraneo',
            'costo_banderazo' => 650.00,
            'costo_km' => 85.00,
            'km_incluidos' => 10,
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
            'origen' => 'Insurgentes Sur 123, CDMX',
            'destino' => 'Periférico 456, CDMX',
            'distancia_km' => 12,
            'tiempo_estimado' => 30,
            'tipo_ruta' => 'local',
            'costo_banderazo' => 450.00,
            'costo_km' => 100.00,
            'km_excedente' => 7,
            'costo_kilometraje' => 1200.00,
            'costo_casetas' => 0,
            'extras' => 0,
            'subtotal' => 1650.00,
            'descuento_porcentaje' => 10,
            'descuento_monto' => 165.00,
            'iva' => 237.60,
            'costo_total' => 1722.60,
            'no_poliza' => 'POL-2026-001',
            'marca' => 'Nissan',
            'modelo' => 'Versa 2020',
            'color' => 'Gris',
            'placas' => 'NVE-1234',
            'con_peaje' => false,
            'num_casetas' => 0,
            'costo_casetas' => 0,
            'cobertura' => 'parcial',
            'convenio_id' => 1,
            'notas' => 'Cliente frecuente, aplicar descuento por convenio',
            'created_by' => $cotUser->id,
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
            'origen' => 'Av. Universidad 456, CDMX',
            'destino' => 'Taller Mecánico - Calzada Taxqueña',
            'distancia_km' => 8,
            'tiempo_estimado' => 20,
            'tipo_ruta' => 'local',
            'costo_banderazo' => 500.00,
            'costo_km' => 90.00,
            'km_excedente' => 3,
            'costo_kilometraje' => 720.00,
            'costo_casetas' => 0,
            'extras' => 200.00,
            'subtotal' => 1420.00,
            'descuento_porcentaje' => 0,
            'descuento_monto' => 0,
            'iva' => 227.20,
            'costo_total' => 1647.20,
            'no_poliza' => 'POL-2026-002',
            'marca' => 'Toyota',
            'modelo' => 'Corolla 2022',
            'color' => 'Blanco',
            'placas' => 'TOC-5678',
            'con_peaje' => false,
            'num_casetas' => 0,
            'costo_casetas' => 0,
            'cobertura' => 'sin_cobertura',
            'notas' => 'Servicio urgente, vehículo descompuesto en vía pública',
            'created_by' => $cotUser->id,
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
            'origen' => 'Paseo de la Reforma 789, CDMX',
            'destino' => 'Autopista México-Puebla Km 45',
            'distancia_km' => 65,
            'tiempo_estimado' => 90,
            'tipo_ruta' => 'foraneo',
            'costo_banderazo' => 650.00,
            'costo_km' => 85.00,
            'km_excedente' => 55,
            'costo_kilometraje' => 5525.00,
            'costo_casetas' => 3,
            'extras' => 0,
            'subtotal' => 6175.00,
            'descuento_porcentaje' => 15,
            'descuento_monto' => 926.25,
            'iva' => 839.80,
            'costo_total' => 6088.55,
            'no_poliza' => 'POL-2026-003',
            'marca' => 'Kenworth',
            'modelo' => 'T680 2023',
            'color' => 'Rojo',
            'placas' => 'KEN-9012',
            'con_peaje' => true,
            'num_casetas' => 3,
            'costo_casetas' => 570.00,
            'cobertura' => 'total',
            'convenio_id' => $convenioGnp->id,
            'notas' => 'Unidad siniestrada, requiere grúa de plataforma pesada',
            'created_by' => $adminUser->id,
            'estatus' => 'aprobado',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(1),
        ]);

        Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente4->id,
            'aseguradora_id' => $bbva->id,
            'tipo_servicio_id' => $auxilio->id,
            'folio' => 'COT-0004',
            'origen' => 'Periférico Sur 1000, CDMX',
            'destino' => 'Periférico Sur 1000, CDMX (mismo lugar)',
            'distancia_km' => 0,
            'tiempo_estimado' => 15,
            'tipo_ruta' => 'local',
            'costo_banderazo' => 350.00,
            'costo_km' => 0,
            'km_excedente' => 0,
            'costo_kilometraje' => 0,
            'costo_casetas' => 0,
            'extras' => 0,
            'subtotal' => 350.00,
            'descuento_porcentaje' => 0,
            'descuento_monto' => 0,
            'iva' => 56.00,
            'costo_total' => 406.00,
            'no_poliza' => 'POL-2026-004',
            'marca' => 'Volkswagen',
            'modelo' => 'Jetta 2019',
            'color' => 'Azul',
            'placas' => 'VWJ-3456',
            'con_peaje' => false,
            'num_casetas' => 0,
            'costo_casetas' => 0,
            'cobertura' => 'sin_cobertura',
            'notas' => 'Cambio de llanta, servicio express',
            'created_by' => $cotUser->id,
            'estatus' => 'borrador',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        Cotizacion::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente5->id,
            'aseguradora_id' => $mapfre->id,
            'tipo_servicio_id' => $rescate->id,
            'folio' => 'COT-0005',
            'origen' => 'Av. Constitución 500, Monterrey',
            'destino' => 'Autopista Monterrey-Saltillo Km 120',
            'distancia_km' => 180,
            'tiempo_estimado' => 150,
            'tipo_ruta' => 'foraneo',
            'costo_banderazo' => 800.00,
            'costo_km' => 95.00,
            'km_excedente' => 170,
            'costo_kilometraje' => 17100.00,
            'costo_casetas' => 4,
            'extras' => 500.00,
            'subtotal' => 18400.00,
            'descuento_porcentaje' => 0,
            'descuento_monto' => 0,
            'iva' => 2944.00,
            'costo_total' => 21344.00,
            'no_poliza' => 'POL-2026-005',
            'marca' => 'Freightliner',
            'modelo' => 'Cascadia 2024',
            'color' => 'Blanco',
            'placas' => 'FRE-7890',
            'con_peaje' => true,
            'num_casetas' => 4,
            'costo_casetas' => 760.00,
            'cobertura' => 'sin_cobertura',
            'notas' => 'Tráiler volcado, requiere grúa de gran capacidad',
            'created_by' => $adminUser->id,
            'estatus' => 'rechazado',
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(6),
        ]);

        // 9. Servicios
        Servicio::create([
            'empresa_id' => $empresa->id,
            'cotizacion_id' => $cot1->id,
            'operador_id' => $operador1->id,
            'unidad_id' => $unidad1->id,
            'tipo_servicio_id' => 1,
            'estado' => 'finalizado',
            'fecha_inicio' => now()->subDays(4)->setHour(10)->setMinute(0),
            'fecha_fin' => now()->subDays(4)->setHour(10)->setMinute(45),
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(4),
        ]);

        Servicio::create([
            'empresa_id' => $empresa->id,
            'cotizacion_id' => $cot3->id,
            'operador_id' => $operador1->id,
            'unidad_id' => $unidad3->id,
            'tipo_servicio_id' => 2,
            'estado' => 'en_proceso',
            'fecha_inicio' => now()->setHour(9)->setMinute(30),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Servicio::create([
            'empresa_id' => $empresa->id,
            'cotizacion_id' => $cot2->id,
            'operador_id' => $operador2->id,
            'unidad_id' => $unidad2->id,
            'tipo_servicio_id' => 3,
            'estado' => 'asignado',
            'fecha_inicio' => now()->addHours(2),
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
                'tipo' => $n['tipo'],
                'estado' => 'no_leida',
                'created_at' => now()->subHours(rand(1, 48)),
            ];
            if ($n['usuario_id'] !== null) {
                $data['usuario_id'] = $n['usuario_id'];
            }
            Notificacion::create($data);
        }
    }
}
