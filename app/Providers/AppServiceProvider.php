<?php

namespace App\Providers;

use App\Models\Empresa;
use App\Models\Servicio;
use Carbon\Carbon;
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
                $empresa = Empresa::find($empresaId);
            }
            $view->with('empresa', $empresa);
        });

        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            $serviciosActivos = 0;

            if ($user && $empresaId = session('empresa_id')) {
                $query = Servicio::where('empresa_id', $empresaId)
                    ->whereIn('estado', Servicio::ESTADOS_ACTIVOS);

                if ($user->isOperador()) {
                    $query->where('operador_id', $user->empleado?->operador?->id);
                } elseif ($user->isCliente()) {
                    $query->whereHas('cotizacion', fn($q) => $q->where('usuario_creador_id', $user->id));
                }

                $serviciosActivos = $query->count();
            }

            $view->with('serviciosActivos', $serviciosActivos);
        });
    }
}
