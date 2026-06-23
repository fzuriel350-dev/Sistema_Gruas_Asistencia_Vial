<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use \App\Models\Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'empresa',
        'telefono',
        'direccion',
        'contacto',
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

    public function serviciosConfigurados()
    {
        return $this->hasMany(ServicioConfigurado::class);
    }
}
