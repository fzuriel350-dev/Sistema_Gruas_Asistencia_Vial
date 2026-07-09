<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operador extends Model
{
    use SoftDeletes, Traits\BelongsToEmpresa;

    protected $table = 'operadores';

    protected $fillable = [
        'empresa_id',
        'empleado_id',
        'licencia_tipo',
        'licencia_año_vencimiento',
        'licencia_vencimiento_federal',
        'disponible',
        'puntos_acumulados',
    ];

    protected function casts(): array
    {
        return [
            'licencia_año_vencimiento' => 'date',
            'licencia_vencimiento_federal' => 'date',
            'disponible' => 'boolean',
            'puntos_acumulados' => 'integer',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function unidades()
    {
        return $this->hasMany(Unidad::class);
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function cargasDiesel()
    {
        return $this->hasMany(CargaDiesel::class);
    }
}
