<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Servicio;
use App\Models\Operador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        if ($user->isCliente()) {
            $clienteId = Cliente::where('usuario_id', $user->id)->value('id');

            $cotizacionesPendientes = Cotizacion::where('empresa_id', $empresaId)
                ->where('cliente_id', $clienteId)->where('estatus', 'pendiente')->count();
            $serviciosActivos = 0;
            $serviciosFinalizados = 0;
            $servicioActivo = null;

            if ($clienteId) {
                $serviciosQuery = Servicio::where('empresa_id', $empresaId)
                    ->whereHas('cotizacion', fn($q) => $q->where('cliente_id', $clienteId));
                $serviciosActivos = (clone $serviciosQuery)->whereIn('estado', Servicio::ESTADOS_ACTIVOS)->count();
                $serviciosFinalizados = (clone $serviciosQuery)->where('estado', 'finalizado')->count();
                $servicioActivo = (clone $serviciosQuery)->whereIn('estado', Servicio::ESTADOS_ACTIVOS)
                    ->with('cotizacion', 'operador.empleado')->first();
            }

            return view('dashboard', compact('cotizacionesPendientes', 'serviciosActivos', 'serviciosFinalizados', 'servicioActivo'))
                ->with('role', 'cliente');
        }

        if ($user->isOperador()) {
            $operadorId = $user->empleado?->operador?->id;
            $asignadosHoy = Servicio::where('empresa_id', $empresaId)
                ->where('operador_id', $operadorId)
                ->whereDate('fecha_inicio', today())
                ->count();
            $completadosHoy = Servicio::where('empresa_id', $empresaId)
                ->where('operador_id', $operadorId)
                ->where('estado', 'finalizado')
                ->whereDate('fecha_fin', today())
                ->count();

            return view('dashboard', [
                'role' => 'operador',
                'servicios_asignados' => $asignadosHoy,
                'servicios_hoy' => $completadosHoy,
            ]);
        }

        $stats = [
            'cotizaciones_pendientes' => Cotizacion::where('empresa_id', $empresaId)->where('estatus', 'pendiente')->count(),
            'servicios_activos' => Servicio::where('empresa_id', $empresaId)->whereIn('estado', Servicio::ESTADOS_ACTIVOS)->count(),
            'operadores_disponibles' => Operador::where('empresa_id', $empresaId)->where('disponible', true)->count(),
            'operadores_ocupados' => Operador::where('empresa_id', $empresaId)->where('disponible', false)->count(),
            'ingresos_mes' => Cotizacion::where('empresa_id', $empresaId)
                ->where('estatus', 'aprobado')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('costo_total'),
        ];

        $actividades = $this->getActividadesRecientes($empresaId);
        $servicios = $this->getServiciosRecientes($empresaId);
        $dias = $this->getServiciosPorDia($empresaId);
        $nuevosClientes = $this->getNuevosClientes($empresaId);

        return view('dashboard', compact('stats', 'actividades', 'servicios', 'dias', 'nuevosClientes'))
            ->with('role', 'admin');
    }

    private function getActividadesRecientes(int $empresaId): array
    {
        $recientes = Cotizacion::where('empresa_id', $empresaId)
            ->with('cliente')
            ->latest()
            ->take(5)
            ->get();

        $actividades = [];
        foreach ($recientes as $c) {
            $cliente = $c->cliente?->nombre ?? 'Desconocido';
            $actividades[] = [
                'text' => "{$c->folio} <strong>" . ucfirst($c->estatus) . "</strong> — {$cliente}",
                'time' => $c->created_at->diffForHumans(),
                'dot' => match($c->estatus) {
                    'aprobado' => 'success',
                    'rechazado' => 'danger',
                    'pendiente' => 'active',
                    default => 'pending',
                },
            ];
        }
        return $actividades;
    }

    private function getServiciosRecientes(int $empresaId): array
    {
        return Servicio::where('empresa_id', $empresaId)
            ->with('cotizacion.cliente')
            ->latest()
            ->take(4)
            ->get()
            ->map(fn($s) => [
                'folio' => $s->cotizacion?->folio ?? 'SVC-' . $s->id,
                'cliente' => $s->cotizacion?->cliente?->nombre ?? '—',
                'origen' => $s->cotizacion?->origen_direccion ?? '—',
                'destino' => $s->cotizacion?->destino_direccion ?? '—',
                'estado' => str_replace('_', ' ', ucfirst($s->estado)),
                'class' => match($s->estado) {
                    'inicio_servicio', 'en_sitio_origen', 'en_carga', 'en_transito', 'en_sitio_destino' => 'active',
                    'finalizado' => 'success',
                    'cancelado' => 'danger',
                    default => 'pending',
                },
            ])
            ->toArray();
    }

    private function getServiciosPorDia(int $empresaId): array
    {
        $dias = [];
        $labels = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $count = Servicio::where('empresa_id', $empresaId)
                ->whereDate('created_at', $fecha)
                ->count();
            $dias[] = [
                'label' => $labels[$fecha->dayOfWeek],
                'value' => $count,
                'height' => max($count * 10, 5),
            ];
        }
        return $dias;
    }

    private function getNuevosClientes(int $empresaId): array
    {
        return User::where('empresa_id', $empresaId)
            ->where('role', User::ROLE_CLIENTE)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($u) => [
                'name' => $u->name,
                'email' => $u->email,
                'time' => $u->created_at->diffForHumans(),
                'created_at' => $u->created_at,
            ])
            ->toArray();
    }
}
