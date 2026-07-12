<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aseguradora extends Model
{
    use SoftDeletes, Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'telefono',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }

    public function tiposServicio()
    {
        return $this->belongsToMany(TipoServicio::class, 'aseguradora_tipo_servicio', 'aseguradora_id', 'tipo_servicio_id');
    }
}
