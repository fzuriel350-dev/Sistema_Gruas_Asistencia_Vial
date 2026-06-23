<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use \App\Models\Traits\BelongsToEmpresa;

    protected $table = 'notificaciones';

    protected $fillable = [
        'empresa_id',
        'usuario_id',
        'mensaje',
        'tipo',
        'estado',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
