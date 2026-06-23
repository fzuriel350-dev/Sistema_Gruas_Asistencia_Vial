<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Convenio extends Model
{
    use HasFactory, \App\Models\Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'aseguradora_id',
        'nombre',
        'tipo',
        'costo_banderazo',
        'costo_km',
        'km_incluidos',
        'descuento',
        'cobertura',
    ];

    const TIPO = ['local', 'foraneo'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }
}
