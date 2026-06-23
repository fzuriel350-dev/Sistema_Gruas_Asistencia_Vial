<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use \App\Models\Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'cotizacion_id',
        'operador_id',
        'unidad_id',
        'tipo_servicio_id',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
        ];
    }

    const ESTADOS = ['asignado', 'en_proceso', 'finalizado', 'cancelado'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function operador()
    {
        return $this->belongsTo(Operador::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class);
    }
}
