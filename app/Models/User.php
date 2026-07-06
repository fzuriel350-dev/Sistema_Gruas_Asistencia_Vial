<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, \App\Models\Traits\BelongsToEmpresa;

    const ROLE_ADMIN = 'admin';
    const ROLE_COTIZADOR = 'cotizador';
    const ROLE_OPERADOR = 'operador';
    const ROLE_CLIENTE = 'cliente';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'empresa_id',
        'empleado_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCotizador(): bool
    {
        return $this->role === self::ROLE_COTIZADOR;
    }

    public function isOperador(): bool
    {
        return $this->role === self::ROLE_OPERADOR;
    }

    public function isEmpleado(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_COTIZADOR, self::ROLE_OPERADOR]);
    }

    public function isCliente(): bool
    {
        return $this->role === self::ROLE_CLIENTE;
    }

    public function cotizacionesCreadas()
    {
        return $this->hasMany(Cotizacion::class, 'usuario_creador_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'usuario_id');
    }

    public function solicitudesCancelacion()
    {
        return $this->hasMany(AutorizacionCancelacion::class, 'usuario_solicitante_id');
    }

    public function resolucionesCancelacion()
    {
        return $this->hasMany(AutorizacionCancelacion::class, 'usuario_resolutor_id');
    }
}
