<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizador extends Model
{
    use SoftDeletes, Traits\BelongsToEmpresa;

    protected $table = 'cotizadores';

    protected $fillable = [
        'empresa_id',
        'empleado_id',
        'especialidades',
        'zona_cobertura',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
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

    public function servicios(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Servicio::class);
    }
}
