<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Cotizacion;
use App\Models\Factura;
use App\Models\Operador;
use App\Models\TipoServicio;
use App\Models\Unidad;
use App\Models\Oficina;
use App\Models\AutorizacionCancelacion;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
            'cargos_extras' => ['nullable', 'numeric', 'min:0'],
            'motivo_cargos_extras' => ['nullable', 'string', 'max:500'],
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
        $servicio = Servicio::create($data);

        Operador::where('id', $data['operador_id'])->update(['disponible' => false]);

        $empleados = User::where('empresa_id', session('empresa_id'))
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        foreach ($empleados as $emp) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $emp->id,
                'mensaje' => "Servicio #{$servicio->id} creado y asignado al operador.",
                'tipo' => 'servicio',
                'estado' => 'no_leida',
            ]);
            Cache::forget("notificaciones_no_leidas_{$emp->id}");
        }

        $cotizacion = $servicio->cotizacion;
        if ($cotizacion && $cotizacion->usuario_creador_id) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $cotizacion->usuario_creador_id,
                'mensaje' => "Tu servicio #{$servicio->id} ha sido creado. Folio cotización: {$cotizacion->folio}.",
                'tipo' => 'servicio',
                'estado' => 'no_leida',
            ]);
            Cache::forget("notificaciones_no_leidas_{$cotizacion->usuario_creador_id}");
        }

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

        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado')
            ->get();

        $unidades = Unidad::where('empresa_id', session('empresa_id'))
            ->where('activo', true)
            ->get();

        return view('servicios.show', compact('servicio', 'progreso', 'operadores', 'unidades'));
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
                + ($cotizacion->incluye_peajes ? $cotizacion->costo_aprox_casetas : 0)
                + ($servicio->cargos_extras ?? 0);
            $updateData['costo_final_real'] = $costoCalculado;
            Operador::where('id', $servicio->operador_id)->update(['disponible' => true]);
        }

        $servicio->update($updateData);

        $notificarEstados = ['inicio_servicio', 'finalizado'];
        if (in_array($nuevoEstado, $notificarEstados)) {
            $mensajeMap = [
                'inicio_servicio' => "El servicio #{$servicio->id} ha iniciado.",
                'finalizado' => "El servicio #{$servicio->id} ha sido finalizado.",
            ];

            $empleados = User::where('empresa_id', session('empresa_id'))
                ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
                ->get();

            foreach ($empleados as $emp) {
                Notificacion::create([
                    'empresa_id' => session('empresa_id'),
                    'usuario_id' => $emp->id,
                    'mensaje' => $mensajeMap[$nuevoEstado],
                    'tipo' => 'servicio',
                    'estado' => 'no_leida',
                ]);
                Cache::forget("notificaciones_no_leidas_{$emp->id}");
            }

            $cotizacion = $servicio->cotizacion;
            if ($cotizacion && $cotizacion->usuario_creador_id) {
                Notificacion::create([
                    'empresa_id' => session('empresa_id'),
                    'usuario_id' => $cotizacion->usuario_creador_id,
                    'mensaje' => $mensajeMap[$nuevoEstado],
                    'tipo' => 'servicio',
                    'estado' => 'no_leida',
                ]);
                Cache::forget("notificaciones_no_leidas_{$cotizacion->usuario_creador_id}");
            }
        }

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

        $usuarios = User::where('empresa_id', session('empresa_id'))
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        foreach ($usuarios as $u) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $u->id,
                'mensaje' => "El operador {$operadorActual?->empleado?->nombreCompleto()} liberó el servicio #{$servicio->id}. Motivo: {$data['motivo_liberacion']}. Se necesita asignar un nuevo operador.",
                'canal' => 'sistema',
                'tipo' => 'servicio',
                'estado' => 'no_leida',
            ]);
        }

        return redirect()->route('servicios.show', $servicio)
            ->with('warning', 'Servicio liberado. El cotizador recibió una notificación para asignar un nuevo operador.');
    }

    public function asignarOperador(Request $request, Servicio $servicio)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isCotizador()) {
            abort(403);
        }

        if ($servicio->operador_id) {
            return back()->with('error', 'El servicio ya tiene un operador asignado.');
        }

        if (in_array($servicio->estado, ['finalizado', 'cancelado'])) {
            return back()->with('error', 'No se puede asignar operador a un servicio finalizado o cancelado.');
        }

        $data = $request->validate([
            'operador_id' => ['required', 'exists:operadores,id'],
            'unidad_id' => ['nullable', 'exists:unidades,id'],
        ]);

        $operador = Operador::where('empresa_id', session('empresa_id'))
            ->where('id', $data['operador_id'])
            ->firstOrFail();

        if (!$operador->disponible) {
            return back()->with('error', 'El operador seleccionado no está disponible.');
        }

        if (!empty($data['unidad_id'])) {
            $unidad = Unidad::where('empresa_id', session('empresa_id'))
                ->where('id', $data['unidad_id'])
                ->firstOrFail();
            if (!$unidad->activo) {
                return back()->with('error', 'La unidad seleccionada no está disponible.');
            }
        }

        $servicio->update([
            'operador_id' => $operador->id,
            'unidad_id' => $data['unidad_id'] ?? null,
            'estado' => 'en_proceso',
        ]);

        $operador->update(['disponible' => false]);

        if (!empty($data['unidad_id'])) {
            $unidad->update(['disponible' => false]);
        }

        Notificacion::create([
            'empresa_id' => session('empresa_id'),
            'usuario_id' => $operador->user_id ?? null,
            'mensaje' => "Se te ha asignado al servicio #{$servicio->id}. Cliente: {$servicio->cotizacion?->cliente?->nombre}.",
            'canal' => 'sistema',
            'tipo' => 'servicio',
            'estado' => 'no_leida',
        ]);

        $empleados = User::where('empresa_id', session('empresa_id'))
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        foreach ($empleados as $emp) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $emp->id,
                'mensaje' => "Operador {$operador->empleado?->nombre} asignado al servicio #{$servicio->id}.",
                'tipo' => 'servicio',
                'estado' => 'no_leida',
            ]);
            Cache::forget("notificaciones_no_leidas_{$emp->id}");
        }

        $cotizacion = $servicio->cotizacion;
        if ($cotizacion && $cotizacion->usuario_creador_id) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $cotizacion->usuario_creador_id,
                'mensaje' => "Operador {$operador->empleado?->nombre} fue asignado a tu servicio #{$servicio->id}.",
                'tipo' => 'servicio',
                'estado' => 'no_leida',
            ]);
            Cache::forget("notificaciones_no_leidas_{$cotizacion->usuario_creador_id}");
        }

        return redirect()->route('servicios.show', $servicio)
            ->with('success', "Operador {$operador->empleado?->nombre} asignado al servicio.");
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

        $cotizacion = $servicio->cotizacion;
        if ($cotizacion && $cotizacion->usuario_creador_id) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $cotizacion->usuario_creador_id,
                'mensaje' => "Tu servicio #{$servicio->id} fue cancelado. Motivo: {$data['motivo_cancelacion']}.",
                'tipo' => 'servicio',
                'estado' => 'no_leida',
            ]);
            Cache::forget("notificaciones_no_leidas_{$cotizacion->usuario_creador_id}");
        }

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

    public function generarFactura(Servicio $servicio)
    {
        $this->authorize('admin');

        if ($servicio->estado !== 'finalizado') {
            return back()->with('error', 'Solo se pueden generar facturas de servicios finalizados.');
        }

        if ($servicio->factura) {
            return back()->with('error', 'Este servicio ya tiene una factura generada.');
        }

        $cotizacion = $servicio->cotizacion;
        $cliente = $cotizacion?->cliente;

        if (!$cliente) {
            return back()->with('error', 'No se encontró el cliente asociado al servicio.');
        }

        $subtotal = $servicio->costo_final_real ?? 0;
        $iva = round($subtotal * 0.16, 2);
        $total = round($subtotal + $iva, 2);

        $folio = 'FAC-' . strtoupper(Str::random(8));

        Factura::create([
            'empresa_id' => session('empresa_id'),
            'cliente_id' => $cliente->id,
            'servicio_id' => $servicio->id,
            'folio_factura' => $folio,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'estatus' => 'vigente',
        ]);

        $empleados = User::where('empresa_id', session('empresa_id'))
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        foreach ($empleados as $emp) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $emp->id,
                'mensaje' => "Factura {$folio} generada para el servicio #{$servicio->id}. Total: \${$total}.",
                'tipo' => 'factura',
                'estado' => 'no_leida',
            ]);
            Cache::forget("notificaciones_no_leidas_{$emp->id}");
        }

        if ($cotizacion && $cotizacion->usuario_creador_id) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $cotizacion->usuario_creador_id,
                'mensaje' => "Factura {$folio} generada para tu servicio #{$servicio->id}. Total: \${$total}.",
                'tipo' => 'factura',
                'estado' => 'no_leida',
            ]);
            Cache::forget("notificaciones_no_leidas_{$cotizacion->usuario_creador_id}");
        }

        return redirect()->route('servicios.show', $servicio)->with('success', 'Factura generada correctamente.');
    }
}
