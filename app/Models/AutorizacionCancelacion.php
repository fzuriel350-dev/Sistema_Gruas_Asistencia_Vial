<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutorizacionCancelacion extends Model
{
    protected $table = 'autorizaciones_cancelacion';

    protected $fillable = [
        'servicio_id',
        'usuario_solicitante_id',
        'usuario_resolutor_id',
        'motivo_cancelacion',
        'tipo_incidencia',
        'estatus',
        'fecha_solicitud',
        'fecha_resolucion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'datetime',
            'fecha_resolucion' => 'datetime',
        ];
    }

    const TIPOS_INCIDENCIA = [
        'cliente_cancela',
        'operador_siniestro',
        'falla_mecanica',
        'llanta_ponchada',
        'accidente',
        'duplicado',
        'otro',
    ];

    const ESTATUS = [
        'pendiente',
        'cancelado_por_cotizador',
        'rechazada',
        'liberado',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function usuarioSolicitante()
    {
        return $this->belongsTo(User::class, 'usuario_solicitante_id');
    }

    public function usuarioResolutor()
    {
        return $this->belongsTo(User::class, 'usuario_resolutor_id');
    }
}
