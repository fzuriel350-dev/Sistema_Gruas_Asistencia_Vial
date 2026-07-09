<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotizacion extends Model
{
    use HasFactory, \App\Models\Traits\BelongsToEmpresa;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'aseguradora_id',
        'tipo_servicio_id',
        'folio',
        'origen_direccion',
        'destino_direccion',
        'origen_lat',
        'origen_lng',
        'destino_lat',
        'destino_lng',
        'distancia_km',
        'tiempo_estimado_minutos',
        'costo_banderazo',
        'costo_km',
        'km_excedente',
        'incluye_peajes',
        'costo_aprox_casetas',
        'costo_total',
        'convenio_aplicado_id',
        'usuario_creador_id',
        'estatus',
        'notas',
    ];

    const ESTATUS = ['pendiente', 'aprobado', 'rechazado'];

    public function getOrigenAttribute()
    {
        return $this->origen_direccion;
    }

    public function getDestinoAttribute()
    {
        return $this->destino_direccion;
    }

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

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'convenio_aplicado_id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
