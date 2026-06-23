<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use \App\Models\Traits\BelongsToEmpresa;

    protected $table = 'unidades';

    protected $fillable = [
        'empresa_id',
        'operador_id',
        'marca',
        'tipo',
        'año',
        'placas',
        'numero_serie',
        'seguro_vencimiento',
    ];

    protected function casts(): array
    {
        return [
            'año' => 'integer',
            'seguro_vencimiento' => 'date',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
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
