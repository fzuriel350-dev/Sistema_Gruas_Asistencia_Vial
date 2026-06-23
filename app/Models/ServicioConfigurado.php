<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioConfigurado extends Model
{
    use \App\Models\Traits\BelongsToEmpresa;

    protected $table = 'servicios_configurados';

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'tipo_servicio_id',
        'nombre',
        'tipo',
        'costo_banderazo',
        'costo_km',
        'activo',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id');
    }
}
