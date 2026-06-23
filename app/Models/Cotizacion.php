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
        'origen',
        'destino',
        'distancia_km',
        'tiempo_estimado',
        'tipo_ruta',
        'costo_banderazo',
        'costo_km',
        'km_excedente',
        'costo_total',
        'no_poliza',
        'marca',
        'modelo',
        'color',
        'placas',
        'con_peaje',
        'num_casetas',
        'costo_casetas',
        'costo_kilometraje',
        'extras',
        'subtotal',
        'iva',
        'cobertura',
        'convenio_id',
        'descuento_porcentaje',
        'descuento_monto',
        'notas',
        'created_by',
        'estatus',
    ];

    const TIPO_RUTA = ['local', 'foraneo'];
    const COBERTURA = ['total', 'parcial', 'sin_cobertura'];
    const ESTATUS = ['pendiente', 'aprobado', 'rechazado'];

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
        return $this->belongsTo(User::class, 'created_by');
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
