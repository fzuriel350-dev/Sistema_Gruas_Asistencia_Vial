<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Convenio extends Model
{
    use HasFactory, SoftDeletes, Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'aseguradora_id',
        'tipo_servicio_id',
        'nombre',
        'tipo',
        'costo_banderazo',
        'costo_km',
        'km_incluidos',
        'cubre_casetas_peaje',
        'descuento',
        'cobertura',
    ];

    const TIPO = ['local', 'foraneo'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class);
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'convenio_aplicado_id');
    }
}
