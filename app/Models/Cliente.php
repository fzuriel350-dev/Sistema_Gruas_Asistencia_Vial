<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes, Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'usuario_id',
        'aseguradora_id',
        'nombre',
        'empresa',
        'telefono',
        'direccion',
        'contacto',
        'email',
        'numero_poliza',
        'tipo_cobertura_poliza',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }

    public function servicios()
    {
        return $this->hasManyThrough(Servicio::class, Cotizacion::class, 'cliente_id', 'cotizacion_id');
    }

    public function getUltimoServicioAttribute()
    {
        $max = $this->cotizaciones()->max('created_at');
        return $max ? \Carbon\Carbon::parse($max) : null;
    }

}
