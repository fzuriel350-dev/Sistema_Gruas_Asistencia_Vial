<?php

namespace Database\Seeders;

use App\Models\Cotizacion;
use App\Models\User;
use Illuminate\Database\Seeder;

class CotizacionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        $clientes = User::where('role', 'cliente')->get();
        if ($clientes->isEmpty()) return;

        $cotizaciones = [
            [
                'cliente' => $clientes[0],
                'folio' => 'COT-0001',
                'aseguradora' => 'Quálitas',
                'no_poliza' => 'POL-2026-1001',
                'marca' => 'Nissan', 'modelo' => 'Versa 2023', 'color' => 'Gris', 'placas' => 'ABC-1234',
                'origen' => 'Av. Reforma 123, Col. Centro',
                'destino' => 'Periférico 456, Col. Granada',
                'tipo_servicio' => 'arrastre',
                'distancia_km' => 12.5, 'tiempo_min' => 25,
                'con_peaje' => false, 'num_casetas' => 0, 'costo_casetas' => 0,
                'estado' => 'pendiente',
                'notas' => null,
            ],
            [
                'cliente' => $clientes[0],
                'folio' => 'COT-0002',
                'aseguradora' => 'GNP',
                'no_poliza' => 'POL-2026-1002',
                'marca' => 'Toyota', 'modelo' => 'Corolla 2024', 'color' => 'Blanco', 'placas' => 'DEF-5678',
                'origen' => 'Insurgentes Sur 789, Col. Del Valle',
                'destino' => 'Condesa 321, Col. Hipódromo',
                'tipo_servicio' => 'rescate',
                'distancia_km' => 8.3, 'tiempo_min' => 18,
                'con_peaje' => true, 'num_casetas' => 2, 'costo_casetas' => 280,
                'estado' => 'aprobada',
                'notas' => 'Vehículo accidentado en el lado delantero.',
            ],
            [
                'cliente' => $clientes[0],
                'folio' => 'COT-0003',
                'aseguradora' => 'AXA',
                'no_poliza' => 'POL-2026-1003',
                'marca' => 'Volkswagen', 'modelo' => 'Jetta 2022', 'color' => 'Rojo', 'placas' => 'GHI-9012',
                'origen' => 'Eje Central 555, Col. Buenos Aires',
                'destino' => 'Polanco 111, Col. Polanco',
                'tipo_servicio' => 'arrastre',
                'distancia_km' => 15.0, 'tiempo_min' => 35,
                'con_peaje' => true, 'num_casetas' => 4, 'costo_casetas' => 600,
                'estado' => 'rechazada',
                'notas' => 'Cliente no aceptó el precio.',
            ],
            [
                'cliente' => $clientes[1] ?? $clientes[0],
                'folio' => 'COT-0004',
                'aseguradora' => 'BBVA Seguros',
                'no_poliza' => 'POL-2026-1004',
                'marca' => 'Mazda', 'modelo' => 'CX-5 2024', 'color' => 'Azul', 'placas' => 'JKL-3456',
                'origen' => 'Calzada México Xochimilco 222, Col. Villa Quietud',
                'destino' => 'Tlalpan 333, Col. Tlalpan Centro',
                'tipo_servicio' => 'auxilio_vial',
                'distancia_km' => 6.2, 'tiempo_min' => 14,
                'con_peaje' => false, 'num_casetas' => 0, 'costo_casetas' => 0,
                'estado' => 'aprobada',
                'notas' => 'Llanta ponchada en el lado trasero derecho.',
            ],
            [
                'cliente' => $clientes[2] ?? $clientes[0],
                'folio' => 'COT-0005',
                'aseguradora' => 'Mapfre',
                'no_poliza' => 'POL-2026-1005',
                'marca' => 'Chevrolet', 'modelo' => 'Aveo 2021', 'color' => 'Negro', 'placas' => 'MNO-7890',
                'origen' => 'Av. Universidad 444, Col. Narvarte',
                'destino' => 'Coyoacán 555, Col. Del Carmen',
                'tipo_servicio' => 'arrastre',
                'distancia_km' => 4.8, 'tiempo_min' => 12,
                'con_peaje' => false, 'num_casetas' => 0, 'costo_casetas' => 0,
                'estado' => 'borrador',
                'notas' => null,
            ],
        ];

        foreach ($cotizaciones as $data) {
            $cliente = $data['cliente'];
            unset($data['cliente']);

            $cot = new Cotizacion($data);
            $cot->cliente_id = $cliente->id;
            $cot->created_by = $admin->id;
            $cot->banderazo = 500;
            $cot->costo_km = 120;
            $cot->costo_kilometraje = $data['distancia_km'] * 120;
            $cot->extras = 0;

            $cot->subtotal = $cot->banderazo + $cot->costo_kilometraje + $cot->costo_casetas;
            $cot->iva = round($cot->subtotal * 0.16, 2);
            $cot->total = $cot->subtotal + $cot->iva;
            $cot->cobertura = 'sin_cobertura';

            $cot->save();
        }
    }
}
