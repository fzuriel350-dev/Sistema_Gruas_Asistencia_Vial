<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    use \App\Models\Traits\BelongsToEmpresa;

    protected $table = 'tipos_servicio';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'descripcion',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
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
