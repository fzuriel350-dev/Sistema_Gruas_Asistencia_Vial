<?php

namespace App\Providers;

use App\Models\Empresa;
use App\Models\Notificacion;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('es');
        Gate::define('admin', fn($user) => $user->isAdmin());
        Gate::define('empleado', fn($user) => $user->isEmpleado());

        View::composer('*', function ($view) {
            $empresa = null;
            if ($empresaId = session('empresa_id')) {
                $empresa = Cache::remember("empresa_{$empresaId}", 600, fn() => Empresa::find($empresaId));
            }
            if (!$empresa) {
                $empresa = Cache::remember('empresa_default', 600, fn() => Empresa::first());
            }

            $fechaFormato = $empresa->formato_fecha ?? 'd/m/Y';
            $fechaHoraFormato = $fechaFormato . ' H:i';
            $moneda = $empresa->moneda ?? '$';

            if ($empresa && $empresa->zona_horaria) {
                config(['app.timezone' => $empresa->zona_horaria]);
                date_default_timezone_set($empresa->zona_horaria);
            }
            if ($empresa && $empresa->idioma) {
                Carbon::setLocale($empresa->idioma);
            }

            $view->with(compact('empresa', 'fechaFormato', 'fechaHoraFormato', 'moneda'));
        });

        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            $serviciosActivos = 0;

            if ($user && $empresaId = session('empresa_id')) {
                $cacheKey = "servicios_activos_{$empresaId}";

                if ($user->isOperador()) {
                    $operadorId = $user->empleado?->operador?->id;
                    $cacheKey = "servicios_activos_op_{$operadorId}";
                } elseif ($user->isCliente()) {
                    $cacheKey = "servicios_activos_cliente_{$user->id}";
                }

                $serviciosActivos = Cache::remember($cacheKey, 30, function () use ($user, $empresaId) {
                    $query = Servicio::where('empresa_id', $empresaId)
                        ->whereIn('estado', Servicio::ESTADOS_ACTIVOS);

                    if ($user->isOperador()) {
                        $query->where('operador_id', $user->empleado?->operador?->id);
                    } elseif ($user->isCliente()) {
                        $query->whereHas('cotizacion', fn($q) => $q->where('usuario_creador_id', $user->id));
                    }

                    return $query->count();
                });
            }

            $view->with('serviciosActivos', $serviciosActivos);

            $noLeidas = 0;

            if ($user && $empresaId) {
                $cacheKeyNoLeidas = "notificaciones_no_leidas_{$user->id}";

                $noLeidas = Cache::remember($cacheKeyNoLeidas, 15, function () use ($user, $empresaId) {
                    $query = Notificacion::where('empresa_id', $empresaId)
                        ->where('estado', 'no_leida');

                    if ($user->isCliente()) {
                        $query->where('usuario_id', $user->id);
                    } else {
                        $query->where(function ($q) use ($user) {
                            $q->where('usuario_id', $user->id)
                              ->orWhereNull('usuario_id');
                        });
                    }

                    return $query->count();
                });
            }

            $view->with('noLeidas', $noLeidas);
        });
    }
}
