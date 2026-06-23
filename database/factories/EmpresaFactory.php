<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->company(),
            'color' => '#FFD500',
            'color_secundario' => '#E6A000',
            'tipografia' => 'Inter',
            'telefono_contacto' => fake()->phoneNumber(),
            'email_contacto' => fake()->companyEmail(),
            'moneda' => '$',
            'formato_fecha' => 'd/m/Y',
            'zona_horaria' => 'America/Mexico_City',
            'idioma' => 'es',
            'footer_texto' => fake()->company() . ' — Confianza en el camino',
            'mostrar_precios' => true,
            'notificaciones_habilitadas' => true,
        ];
    }
}
