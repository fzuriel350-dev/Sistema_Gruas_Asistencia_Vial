<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'cotizacion_id',
        'operador_id',
        'unidad_id',
        'oficina_id',
        'tipo_servicio_id',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'kms_salida',
        'kms_llegada_cliente',
        'kms_termino_servicio',
        'kms_regreso_base',
        'kms_cobrados_reales',
        'costo_final_real',
        'cargos_extras',
        'motivo_cargos_extras',
        'observaciones',
        'motivo_liberacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
        ];
    }

    const ESTADOS = ['asignado', 'inicio_servicio', 'en_sitio_origen', 'en_carga', 'en_transito', 'en_sitio_destino', 'finalizado', 'cancelado'];
    const ESTADOS_ACTIVOS = ['asignado', 'inicio_servicio', 'en_sitio_origen', 'en_carga', 'en_transito', 'en_sitio_destino'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function operador()
    {
        return $this->belongsTo(Operador::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class);
    }

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    public function autorizacionesCancelacion()
    {
        return $this->hasMany(AutorizacionCancelacion::class, 'servicio_id');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'servicio_id');
    }
}
