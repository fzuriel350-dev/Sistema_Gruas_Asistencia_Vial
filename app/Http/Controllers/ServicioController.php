<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Cotizacion;
use App\Models\Operador;
use App\Models\TipoServicio;
use App\Models\Unidad;
use App\Models\Oficina;
use App\Models\AutorizacionCancelacion;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Servicio::where('empresa_id', session('empresa_id'))
            ->with('cotizacion.cliente', 'operador.empleado', 'unidad', 'tipoServicio', 'oficina');

        if ($user->isCliente()) {
            $query->whereHas('cotizacion', function ($q) use ($user) {
                $q->where('usuario_creador_id', $user->id);
            });
        } elseif ($user->isOperador()) {
            $query->where('operador_id', $user->empleado?->operador?->id);
        }

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->whereHas('cotizacion', fn($c) => $c->where('folio', 'like', "%{$q}%")
                    ->orWhereHas('cliente', fn($cl) => $cl->where('nombre', 'like', "%{$q}%")))
                    ->orWhereHas('operador.empleado', fn($e) => $e->where('nombre', 'like', "%{$q}%"))
                    ->orWhere('estado', 'like', "%{$q}%");
            });
        }

        if (request('estado')) {
            $query->where('estado', request('estado'));
        }

        $servicios = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        $this->authorize('empleado');
        if (auth()->user()->isOperador()) abort(403);


        $cotizaciones = Cotizacion::where('empresa_id', session('empresa_id'))
            ->where('estatus', 'aprobado')
            ->with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();
        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado')
            ->where('disponible', true)
            ->get();
        $unidades = Unidad::where('empresa_id', session('empresa_id'))->get();
        $tiposServicio = TipoServicio::where('empresa_id', session('empresa_id'))->get();
        $oficinas = Oficina::where('empresa_id', session('empresa_id'))->get();
        return view('servicios.create', compact('cotizaciones', 'operadores', 'unidades', 'tiposServicio', 'oficinas'));
    }

    protected function reglasStore(): array
    {
        return [
            'cotizacion_id' => ['required', 'exists:cotizaciones,id'],
            'operador_id' => ['required', 'exists:operadores,id'],
            'unidad_id' => ['required', 'exists:unidades,id'],
            'tipo_servicio_id' => ['required', 'exists:tipos_servicio,id'],
            'oficina_id' => ['nullable', 'exists:oficinas,id'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'fecha_inicio' => ['required', 'date'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function reglasUpdate(): array
    {
        return [
            'operador_id' => ['required', 'exists:operadores,id'],
            'unidad_id' => ['required', 'exists:unidades,id'],
            'oficina_id' => ['nullable', 'exists:oficinas,id'],
            'estado' => ['required', 'in:' . implode(',', Servicio::ESTADOS)],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'kms_salida' => ['nullable', 'integer', 'min:0'],
            'kms_llegada_cliente' => ['nullable', 'integer', 'min:0'],
            'kms_termino_servicio' => ['nullable', 'integer', 'min:0'],
            'kms_regreso_base' => ['nullable', 'integer', 'min:0'],
            'kms_cobrados_reales' => ['nullable', 'integer', 'min:0'],
            'costo_final_real' => ['nullable', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function mensajesStore(): array
    {
        return [
            'cotizacion_id.required' => 'Selecciona una cotización.',
            'operador_id.required' => 'Selecciona un operador.',
            'unidad_id.required' => 'Selecciona una unidad.',
            'tipo_servicio_id.required' => 'Selecciona un tipo de servicio.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
        ];
    }

    protected function mensajesUpdate(): array
    {
        return [
            'operador_id.required' => 'Selecciona un operador.',
            'unidad_id.required' => 'Selecciona una unidad.',
            'estado.required' => 'El estado es obligatorio.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'kms_salida.min' => 'Los km de salida no pueden ser negativos.',
            'kms_cobrados_reales.min' => 'Los km cobrados no pueden ser negativos.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        if (auth()->user()->isOperador()) abort(403);


        $data = $request->validate($this->reglasStore(), $this->mensajesStore());
        $data['empresa_id'] = session('empresa_id');
        $data['estado'] = 'asignado';
        Servicio::create($data);

        Operador::where('id', $data['operador_id'])->update(['disponible' => false]);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function show(Servicio $servicio)
    {
        $servicio->load('cotizacion.cliente', 'cotizacion.aseguradora', 'operador.empleado', 'unidad', 'tipoServicio', 'oficina');

        $progreso = match ($servicio->estado) {
            'asignado' => 2,
            'inicio_servicio', 'en_sitio_origen', 'en_carga' => 3,
            'en_transito', 'en_sitio_destino' => 4,
            'finalizado' => 5,
            'cancelado' => 0,
            default => 1,
        };

        return view('servicios.show', compact('servicio', 'progreso'));
    }

    public function edit(Servicio $servicio)
    {
        $this->authorize('empleado');
        if (auth()->user()->isOperador()) abort(403);


        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado')
            ->get();
        $unidades = Unidad::where('empresa_id', session('empresa_id'))->get();
        $tiposServicio = TipoServicio::where('empresa_id', session('empresa_id'))->get();
        $oficinas = Oficina::where('empresa_id', session('empresa_id'))->get();
        return view('servicios.edit', compact('servicio', 'operadores', 'unidades', 'tiposServicio', 'oficinas'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $this->authorize('empleado');
        if (auth()->user()->isOperador()) abort(403);


        $data = $request->validate($this->reglasUpdate(), $this->mensajesUpdate());
        $servicio->update($data);

        if (in_array($data['estado'], ['finalizado', 'cancelado'])) {
            Operador::where('id', $data['operador_id'])->update(['disponible' => true]);
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function avanzarEstado(Request $request, Servicio $servicio)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isCotizador() && !auth()->user()->isOperador()) {
            abort(403);
        }

        if ($servicio->operador_id !== auth()->user()->empleado?->operador?->id && !auth()->user()->isAdmin() && !auth()->user()->isCotizador()) {
            abort(403);
        }

        if (in_array($servicio->estado, ['finalizado', 'cancelado'])) {
            return back()->with('error', 'El servicio ya está finalizado o cancelado.');
        }

        $orden = ['asignado', 'inicio_servicio', 'en_sitio_origen', 'en_carga', 'en_transito', 'en_sitio_destino', 'finalizado'];
        $idx = array_search($servicio->estado, $orden);

        if ($idx === false || $idx >= count($orden) - 1) {
            return back()->with('error', 'No se puede avanzar más el estado.');
        }

        $nuevoEstado = $orden[$idx + 1];
        $reglas = ['estado' => 'required|in:' . $nuevoEstado];

        if ($nuevoEstado === 'inicio_servicio') {
            $reglas['kms_salida'] = 'required|integer|min:0';
        }
        if ($nuevoEstado === 'en_sitio_origen') {
            $reglas['kms_llegada_cliente'] = 'required|integer|min:0';
        }
        if ($nuevoEstado === 'en_transito') {
            $reglas['kms_termino_servicio'] = 'required|integer|min:0';
        }
        if ($nuevoEstado === 'finalizado') {
            $reglas['kms_regreso_base'] = 'required|integer|min:0';
            $reglas['kms_cobrados_reales'] = 'required|integer|min:0';
        }

        $data = $request->validate($reglas, [
            'kms_salida.required' => 'Registra el kilometraje de salida.',
            'kms_llegada_cliente.required' => 'Registra el kilometraje al llegar al cliente.',
            'kms_termino_servicio.required' => 'Registra el kilometraje al terminar el servicio.',
            'kms_regreso_base.required' => 'Registra el kilometraje de regreso a base.',
            'kms_cobrados_reales.required' => 'Registra los kilómetros a cobrar.',
            'costo_final_real.required' => 'Registra el costo final del servicio.',
        ]);

        $updateData = ['estado' => $nuevoEstado];

        if ($nuevoEstado === 'inicio_servicio') {
            if (!$servicio->fecha_inicio) $updateData['fecha_inicio'] = now();
            $updateData['kms_salida'] = $data['kms_salida'];
        }
        if ($nuevoEstado === 'en_sitio_origen') {
            $updateData['kms_llegada_cliente'] = $data['kms_llegada_cliente'];
        }
        if ($nuevoEstado === 'en_transito') {
            $updateData['kms_termino_servicio'] = $data['kms_termino_servicio'];
        }
        if ($nuevoEstado === 'finalizado') {
            $updateData['fecha_fin'] = now();
            $updateData['kms_regreso_base'] = $data['kms_regreso_base'];
            $updateData['kms_cobrados_reales'] = $data['kms_cobrados_reales'];
            $cotizacion = $servicio->cotizacion;
            $costoCalculado = $cotizacion->costo_banderazo
                + ($data['kms_cobrados_reales'] * $cotizacion->costo_km)
                + ($cotizacion->incluye_peajes ? $cotizacion->costo_aprox_casetas : 0);
            $updateData['costo_final_real'] = $costoCalculado;
            Operador::where('id', $servicio->operador_id)->update(['disponible' => true]);
        }

        $servicio->update($updateData);

        $mensajes = [
            'inicio_servicio' => '✅ Servicio iniciado. Kilometraje de salida registrado.',
            'en_sitio_origen' => '📍 Llegada al origen registrada.',
            'en_carga' => '⬆️ Vehículo en carga.',
            'en_transito' => '🚛 En tránsito hacia el destino.',
            'en_sitio_destino' => '📍 Llegada al destino.',
            'finalizado' => '✅ Servicio finalizado correctamente.',
        ];

        return redirect()->route('servicios.show', $servicio)
            ->with('success', $mensajes[$nuevoEstado] ?? "Estado actualizado a: {$nuevoEstado}");
    }

    public function liberar(Request $request, Servicio $servicio)
    {
        $user = auth()->user();
        if (!$user->isOperador() || $servicio->operador_id !== $user->empleado?->operador?->id) {
            abort(403);
        }

        if (in_array($servicio->estado, ['finalizado', 'cancelado'])) {
            return back()->with('error', 'El servicio ya está finalizado o cancelado.');
        }

        $data = $request->validate([
            'tipo_incidencia' => ['required', 'in:' . implode(',', AutorizacionCancelacion::TIPOS_INCIDENCIA)],
            'motivo_liberacion' => ['required', 'string', 'max:1000'],
        ]);

        $operadorActual = $servicio->operador;

        $servicio->update([
            'motivo_liberacion' => $data['motivo_liberacion'],
            'operador_id' => null,
            'unidad_id' => null,
        ]);

        if ($operadorActual) {
            $operadorActual->update(['disponible' => true]);
        }

        AutorizacionCancelacion::create([
            'servicio_id' => $servicio->id,
            'usuario_solicitante_id' => $user->id,
            'usuario_resolutor_id' => $user->id,
            'motivo_cancelacion' => $data['motivo_liberacion'],
            'tipo_incidencia' => $data['tipo_incidencia'],
            'estatus' => 'liberado',
            'fecha_solicitud' => now(),
            'fecha_resolucion' => now(),
        ]);

        $nuevoOperador = Operador::where('empresa_id', session('empresa_id'))
            ->where('disponible', true)
            ->where('id', '!=', $operadorActual?->id)
            ->inRandomOrder()
            ->first();

        if ($nuevoOperador) {
            $servicio->update(['operador_id' => $nuevoOperador->id]);
            $nuevoOperador->update(['disponible' => false]);

            return redirect()->route('servicios.show', $servicio)
                ->with('success', "Servicio liberado y reasignado a {$nuevoOperador->empleado?->nombre}.");
        }

        return redirect()->route('servicios.show', $servicio)
            ->with('warning', 'Servicio liberado. No hay operadores disponibles para reasignar. Asigna uno manualmente.');
    }

    public function cancelarPorCotizador(Request $request, Servicio $servicio)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isCotizador()) {
            abort(403);
        }

        if (in_array($servicio->estado, ['finalizado', 'cancelado'])) {
            return back()->with('error', 'El servicio ya está finalizado o cancelado.');
        }

        $data = $request->validate([
            'tipo_incidencia' => ['required', 'in:' . implode(',', AutorizacionCancelacion::TIPOS_INCIDENCIA)],
            'motivo_cancelacion' => ['required', 'string', 'max:1000'],
        ]);

        $servicio->update(['estado' => 'cancelado']);

        if ($servicio->operador_id) {
            Operador::where('id', $servicio->operador_id)->update(['disponible' => true]);
        }

        AutorizacionCancelacion::create([
            'servicio_id' => $servicio->id,
            'usuario_solicitante_id' => $user->id,
            'usuario_resolutor_id' => $user->id,
            'motivo_cancelacion' => $data['motivo_cancelacion'],
            'tipo_incidencia' => $data['tipo_incidencia'],
            'estatus' => 'cancelado_por_cotizador',
            'fecha_solicitud' => now(),
            'fecha_resolucion' => now(),
        ]);

        return redirect()->route('servicios.index')->with('success', 'Servicio cancelado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $this->authorize('empleado');
        if (auth()->user()->isOperador()) abort(403);


        if ($servicio->operador_id) {
            Operador::where('id', $servicio->operador_id)->update(['disponible' => true]);
        }
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado.');
    }
}
