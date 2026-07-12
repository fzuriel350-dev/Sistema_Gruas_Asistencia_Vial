<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidad extends Model
{
    use SoftDeletes, Traits\BelongsToEmpresa;

    protected $table = 'unidades';

    protected $fillable = [
        'empresa_id',
        'oficina_id',
        'operador_id',
        'marca',
        'tipo',
        'modelo',
        'año',
        'placas',
        'numero_economico',
        'numero_serie',
        'seguro_vencimiento',
        'estado_emplacado',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'año' => 'integer',
            'seguro_vencimiento' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    public function operador()
    {
        return $this->belongsTo(Operador::class);
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
