<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'logo',
        'logo_oscuro',
        'favicon',
        'color',
        'color_secundario',
        'tipografia',
        'modo_oscuro',
        'telefono_contacto',
        'email_contacto',
        'whatsapp',
        'direccion',
        'sitio_web',
        'moneda',
        'formato_fecha',
        'zona_horaria',
        'idioma',
        'footer_texto',
        'mostrar_precios',
        'notificaciones_habilitadas',
    ];

    protected function casts(): array
    {
        return [
            'modo_oscuro' => 'boolean',
            'mostrar_precios' => 'boolean',
            'notificaciones_habilitadas' => 'boolean',
        ];
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function aseguradoras()
    {
        return $this->hasMany(Aseguradora::class);
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class);
    }

    public function operadores()
    {
        return $this->hasMany(Operador::class);
    }

    public function unidades()
    {
        return $this->hasMany(Unidad::class);
    }

    public function tiposServicio()
    {
        return $this->hasMany(TipoServicio::class);
    }

    public function serviciosConfigurados()
    {
        return $this->hasMany(ServicioConfigurado::class);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }
}
