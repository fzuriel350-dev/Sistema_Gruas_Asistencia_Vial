<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Cotizacion;
use App\Models\Operador;
use App\Models\TipoServicio;
use App\Models\Unidad;
use App\Models\Oficina;
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

    public function store(Request $request)
    {
        $this->authorize('empleado');
        if (auth()->user()->isOperador()) abort(403);


        $data = $request->validate([
            'cotizacion_id' => 'required|exists:cotizaciones,id',
            'operador_id' => 'required|exists:operadores,id',
            'unidad_id' => 'required|exists:unidades,id',
            'tipo_servicio_id' => 'required|exists:tipos_servicio,id',
            'oficina_id' => 'nullable|exists:oficinas,id',
            'descripcion' => 'nullable|string|max:500',
            'fecha_inicio' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);
        $data['empresa_id'] = session('empresa_id');
        $data['estado'] = 'asignado';
        Servicio::create($data);

        Operador::where('id', $data['operador_id'])->update(['disponible' => false]);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function show(Servicio $servicio)
    {
        $servicio->load('cotizacion.cliente', 'cotizacion.aseguradora', 'operador.empleado', 'unidad', 'tipoServicio', 'oficina');
        return view('servicios.show', compact('servicio'));
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


        $data = $request->validate([
            'operador_id' => 'required|exists:operadores,id',
            'unidad_id' => 'required|exists:unidades,id',
            'oficina_id' => 'nullable|exists:oficinas,id',
            'estado' => 'required|in:' . implode(',', Servicio::ESTADOS),
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'kms_salida' => 'nullable|integer|min:0',
            'kms_llegada_cliente' => 'nullable|integer|min:0',
            'kms_termino_servicio' => 'nullable|integer|min:0',
            'kms_regreso_base' => 'nullable|integer|min:0',
            'kms_cobrados_reales' => 'nullable|integer|min:0',
            'costo_final_real' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);
        $servicio->update($data);

        if (in_array($data['estado'], ['finalizado', 'cancelado'])) {
            Operador::where('id', $data['operador_id'])->update(['disponible' => true]);
        }

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
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
