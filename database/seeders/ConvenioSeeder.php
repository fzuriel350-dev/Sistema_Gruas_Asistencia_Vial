<?php

namespace Database\Seeders;

use App\Models\Convenio;
use Illuminate\Database\Seeder;

class ConvenioSeeder extends Seeder
{
    public function run(): void
    {
        Convenio::insert([
            [
                'nombre' => 'BBVA Seguros — Premium',
                'aseguradora' => 'BBVA Seguros',
                'nivel' => 'premium',
                'descuento' => 15,
                'cobertura' => 'total',
                'descripcion' => 'Convenio premium con descuento del 15% y cobertura total.',
                'activo' => true,
            ],
            [
                'nombre' => 'Quálitas — Básico',
                'aseguradora' => 'Quálitas',
                'nivel' => 'basico',
                'descuento' => 5,
                'cobertura' => 'parcial',
                'descripcion' => 'Convenio básico con 5% de descuento y cobertura parcial.',
                'activo' => true,
            ],
            [
                'nombre' => 'GNP — Premium',
                'aseguradora' => 'GNP',
                'nivel' => 'premium',
                'descuento' => 10,
                'cobertura' => 'total',
                'descripcion' => 'Convenio premium con 10% de descuento y cobertura total.',
                'activo' => true,
            ],
            [
                'nombre' => 'AXA — Básico',
                'aseguradora' => 'AXA',
                'nivel' => 'basico',
                'descuento' => 0,
                'cobertura' => 'sin_cobertura',
                'descripcion' => 'Convenio básico sin descuento ni cobertura.',
                'activo' => true,
            ],
            [
                'nombre' => 'Mapfre — Premium',
                'aseguradora' => 'Mapfre',
                'nivel' => 'premium',
                'descuento' => 12,
                'cobertura' => 'total',
                'descripcion' => 'Convenio premium con 12% de descuento y cobertura total.',
                'activo' => true,
            ],
        ]);
    }
}
