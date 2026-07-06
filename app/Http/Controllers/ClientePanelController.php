<?php

namespace App\Http\Controllers;

use App\Models\AutorizacionCancelacion;
use App\Models\Cotizacion;
use App\Models\Servicio;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientePanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isCliente()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $cotizaciones = Cotizacion::where('empresa_id', $empresaId)
            ->where('usuario_creador_id', $user->id);

        $pendientes = (clone $cotizaciones)->where('estatus', 'pendiente')->count();
        $aprobadas = (clone $cotizaciones)->where('estatus', 'aprobado')->count();
        $rechazadas = (clone $cotizaciones)->where('estatus', 'rechazado')->count();

        $servicios = Servicio::where('empresa_id', $empresaId)
            ->whereHas('cotizacion', fn($q) => $q->where('usuario_creador_id', $user->id));

        $activos = (clone $servicios)->whereIn('estado', Servicio::ESTADOS_ACTIVOS)->count();
        $finalizados = (clone $servicios)->where('estado', 'finalizado')->count();
        $servicioActivo = (clone $servicios)->whereIn('estado', Servicio::ESTADOS_ACTIVOS)
            ->with('cotizacion', 'operador.empleado')
            ->first();

        $serviciosPorMes = (clone $servicios)
            ->selectRaw('strftime("%m", created_at) as mes, count(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $actividades = Cotizacion::where('empresa_id', $empresaId)
            ->where('usuario_creador_id', $user->id)
            ->with('tipoServicio')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'text' => "{$c->folio}: <strong>" . ucfirst($c->estatus) . "</strong>",
                'time' => $c->created_at->diffForHumans(),
                'dot' => match ($c->estatus) {
                    'aprobado' => 'success',
                    'rechazado' => 'danger',
                    default => 'pending',
                },
            ]);

        return view('clientes.dashboard', compact(
            'pendientes', 'aprobadas', 'rechazadas',
            'activos', 'finalizados', 'servicioActivo',
            'serviciosPorMes', 'actividades'
        ));
    }

    public function servicios(Request $request)
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $query = Servicio::where('empresa_id', $empresaId)
            ->whereHas('cotizacion', fn($q) => $q->where('usuario_creador_id', $user->id))
            ->with('cotizacion.tipoServicio', 'operador.empleado', 'unidad', 'tipoServicio');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('cotizacion', fn($qq) => $qq->where('folio', 'like', "%{$q}%"));
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $servicios = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('clientes.servicios', compact('servicios'));
    }

    public function servicioShow(Servicio $servicio)
    {
        $user = auth()->user();
        if ($servicio->cotizacion->usuario_creador_id !== $user->id) {
            abort(403);
        }

        $servicio->load(
            'cotizacion.cliente',
            'cotizacion.tipoServicio',
            'operador.empleado',
            'unidad',
            'tipoServicio'
        );

        $progreso = match ($servicio->estado) {
            'asignado' => 2,
            'inicio_servicio', 'en_sitio_origen', 'en_carga' => 3,
            'en_transito', 'en_sitio_destino' => 4,
            'finalizado' => 5,
            'cancelado' => 0,
            default => 1,
        };

        return view('clientes.servicio-show', compact('servicio', 'progreso'));
    }

    public function cancelarServicio(Request $request, Servicio $servicio)
    {
        $user = auth()->user();
        if ($servicio->cotizacion->usuario_creador_id !== $user->id) {
            abort(403);
        }

        if (in_array($servicio->estado, ['finalizado', 'cancelado'])) {
            return back()->with('error', 'El servicio ya está finalizado o cancelado.');
        }

        $request->validate([
            'motivo' => 'required|string|max:1000',
        ]);

        AutorizacionCancelacion::create([
            'servicio_id' => $servicio->id,
            'usuario_solicitante_id' => $user->id,
            'motivo_cancelacion' => $request->motivo,
            'tipo_incidencia' => 'cliente_cancela',
            'estatus' => 'pendiente',
            'fecha_solicitud' => now(),
        ]);

        return redirect()->route('clientes.servicio-show', $servicio)
            ->with('success', 'Solicitud de cancelación enviada. Un administrador la revisará pronto.');
    }

    public function notificaciones()
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $notificaciones = Notificacion::where('empresa_id', $empresaId)
            ->where('usuario_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('clientes.notificaciones', compact('notificaciones'));
    }

    public function notificacionLeer(Notificacion $notificacione)
    {
        $notificacione->update(['estado' => 'leida']);
        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function notificacionesLeerTodas()
    {
        $user = auth()->user();
        Notificacion::where('usuario_id', $user->id)
            ->where('estado', 'no_leida')
            ->update(['estado' => 'leida']);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function aprobarCotizacion(Request $request, Cotizacion $cotizacione)
    {
        $user = auth()->user();
        if ($cotizacione->usuario_creador_id !== $user->id || $cotizacione->estatus !== 'pendiente') {
            abort(403);
        }

        $cotizacione->update(['estatus' => 'aprobado']);

        $servicio = Servicio::create([
            'empresa_id' => $cotizacione->empresa_id,
            'cotizacion_id' => $cotizacione->id,
            'tipo_servicio_id' => $cotizacione->tipo_servicio_id,
            'estado' => 'asignado',
            'descripcion' => 'Servicio generado por aprobación de cotización.',
        ]);

        Notificacion::create([
            'empresa_id' => $cotizacione->empresa_id,
            'usuario_id' => $user->id,
            'mensaje' => "Cotización {$cotizacione->folio} aprobada. Servicio generado.",
            'tipo' => 'cotizacion_aprobada',
            'estado' => 'no_leida',
        ]);

        return redirect()->route('clientes.servicios')
            ->with('success', 'Cotización aprobada. Servicio generado correctamente.');
    }

    public function rechazarCotizacion(Request $request, Cotizacion $cotizacione)
    {
        $user = auth()->user();
        if ($cotizacione->usuario_creador_id !== $user->id || $cotizacione->estatus !== 'pendiente') {
            abort(403);
        }

        $request->validate(['motivo' => 'nullable|string|max:500']);

        $notas = $cotizacione->notas;
        if ($request->filled('motivo')) {
            $notas = ($notas ? $notas . "\n\n" : '') . "Motivo de rechazo: " . $request->motivo;
        }

        $cotizacione->update(['estatus' => 'rechazado', 'notas' => $notas]);

        Notificacion::create([
            'empresa_id' => $cotizacione->empresa_id,
            'usuario_id' => $user->id,
            'mensaje' => "Cotización {$cotizacione->folio} rechazada.",
            'tipo' => 'cotizacion_rechazada',
            'estado' => 'no_leida',
        ]);

        return redirect()->route('clientes.cotizaciones')
            ->with('success', 'Cotización rechazada.');
    }

    public function cotizaciones(Request $request)
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $query = Cotizacion::where('empresa_id', $empresaId)
            ->where('usuario_creador_id', $user->id)
            ->with('aseguradora', 'tipoServicio');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qq) use ($q) {
                $qq->where('folio', 'like', "%{$q}%")
                    ->orWhere('origen', 'like', "%{$q}%")
                    ->orWhere('destino', 'like', "%{$q}%");
            });
        }
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        $cotizaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('clientes.cotizaciones', compact('cotizaciones'));
    }

    public function perfil()
    {
        return view('clientes.perfil');
    }

    public function updatePerfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
