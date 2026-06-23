<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use \App\Models\Traits\BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'direccion',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->hasOne(User::class);
    }

    public function operador()
    {
        return $this->hasOne(Operador::class);
    }

    public function nombreCompleto(): string
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}
