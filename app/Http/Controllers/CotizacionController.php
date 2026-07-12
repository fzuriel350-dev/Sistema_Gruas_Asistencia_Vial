<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Convenio;
use App\Models\Cliente;
use App\Models\Aseguradora;
use App\Models\Notificacion;
use App\Models\TipoServicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CotizacionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $query = Cotizacion::where('empresa_id', $empresaId)
            ->with('cliente', 'aseguradora', 'tipoServicio');

        if ($user->isCliente()) {
            $query->where('usuario_creador_id', $user->id);
        }

        if (request('aseguradora_id')) {
            $query->where('aseguradora_id', request('aseguradora_id'));
        }

        if (request('tipo_servicio_id')) {
            $query->where('tipo_servicio_id', request('tipo_servicio_id'));
        }

        if (request('estatus')) {
            $query->where('estatus', request('estatus'));
        }

        if ($desde = request('desde')) {
            $query->whereDate('created_at', '>=', $desde);
        }

        if ($hasta = request('hasta')) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('folio', 'like', "%{$q}%")
                    ->orWhere('origen_direccion', 'like', "%{$q}%")
                    ->orWhere('destino_direccion', 'like', "%{$q}%")
                    ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$q}%"));
            });
        }

        $orden = request('orden', 'creacion_desc');
        switch ($orden) {
            case 'cliente_asc':
                $query->orderBy(Cliente::select('nombre')->whereColumn('clientes.id', 'cotizaciones.cliente_id'));
                break;
            case 'cliente_desc':
                $query->orderByDesc(Cliente::select('nombre')->whereColumn('clientes.id', 'cotizaciones.cliente_id'));
                break;
            case 'total_asc':
                $query->orderBy('costo_total');
                break;
            case 'total_desc':
                $query->orderByDesc('costo_total');
                break;
            case 'creacion_asc':
                $query->orderBy('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $cotizaciones = $query->paginate(15);

        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $tiposServicio = TipoServicio::where('empresa_id', $empresaId)->orderBy('nombre')->get();

        return view('cotizaciones.index', compact('cotizaciones', 'aseguradoras', 'tiposServicio'));
    }

    public function create()
    {
        $empresaId = session('empresa_id');
        $clientes = Cliente::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $tiposServicio = TipoServicio::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $convenios = Convenio::where('empresa_id', $empresaId)->get();

        return view('cotizaciones.create', compact('clientes', 'aseguradoras', 'tiposServicio', 'convenios'));
    }

    protected function reglasValidacion(): array
    {
        return [
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'aseguradora_id' => ['required', 'exists:aseguradoras,id'],
            'tipo_servicio_id' => ['required', 'exists:tipos_servicio,id'],
            'origen_direccion' => ['required', 'string', 'max:500', 'regex:/^[\p{L}\p{N}\s\.,#\-\/\'\(\)]+$/u'],
            'origen_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'origen_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'destino_direccion' => ['required', 'string', 'max:500', 'regex:/^[\p{L}\p{N}\s\.,#\-\/\'\(\)]+$/u'],
            'destino_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'destino_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'distancia_km' => ['required', 'numeric', 'min:0.1'],
            'tiempo_estimado_minutos' => ['required', 'integer', 'min:1'],
            'incluye_peajes' => ['boolean'],
            'costo_aprox_casetas' => ['numeric', 'min:0'],
            'costo_banderazo' => ['required', 'numeric', 'min:0'],
            'costo_km' => ['required', 'numeric', 'min:0'],
            'convenio_aplicado_id' => ['nullable', 'exists:convenios,id'],
            'km_excedente' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'origen_direccion.required' => 'La dirección de origen es obligatoria.',
            'origen_direccion.regex' => 'La dirección de origen contiene caracteres no válidos.',
            'destino_direccion.required' => 'La dirección de destino es obligatoria.',
            'destino_direccion.regex' => 'La dirección de destino contiene caracteres no válidos.',
            'distancia_km.required' => 'La distancia es obligatoria.',
            'distancia_km.min' => 'La distancia debe ser mayor a 0.',
            'tiempo_estimado_minutos.required' => 'El tiempo estimado es obligatorio.',
            'tiempo_estimado_minutos.min' => 'El tiempo estimado debe ser al menos 1 minuto.',
            'aseguradora_id.required' => 'Debes seleccionar una aseguradora.',
            'tipo_servicio_id.required' => 'Debes seleccionar un tipo de servicio.',
        ];
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isEmpleado() || auth()->user()->isOperador()) {
            abort(403);
        }

        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());

        $data['empresa_id'] = session('empresa_id');
        $data['incluye_peajes'] = $request->boolean('incluye_peajes');
        $data['costo_aprox_casetas'] = $request->input('costo_aprox_casetas', 0);
        $data['usuario_creador_id'] = auth()->id();
        $data['folio'] = $this->generarFolio();
        $data['km_excedente'] = $request->input('km_excedente', 0);

        $cotizacion = new Cotizacion($data);
        $cotizacion = $this->calcularCostos($cotizacion);
        $cotizacion->estatus = 'pendiente';

        $cotizacion->save();

        $usuarios = User::where('empresa_id', session('empresa_id'))
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        foreach ($usuarios as $u) {
            Notificacion::create([
                'empresa_id' => session('empresa_id'),
                'usuario_id' => $u->id,
                'mensaje' => "Nueva cotización {$cotizacion->folio} creada. Revisión pendiente.",
                'tipo' => 'cotizacion_creada',
                'estado' => 'no_leida',
            ]);
            Cache::forget("notificaciones_no_leidas_{$u->id}");
        }

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización generada correctamente.');
    }

    public function show(Cotizacion $cotizacione)
    {
        $cotizacione->load('cliente', 'aseguradora', 'tipoServicio', 'creador', 'convenio');
        return view('cotizaciones.show', compact('cotizacione'));
    }

    public function edit(Cotizacion $cotizacione)
    {
        $empresaId = session('empresa_id');
        $clientes = Cliente::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $tiposServicio = TipoServicio::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $convenios = Convenio::where('empresa_id', $empresaId)->get();

        return view('cotizaciones.edit', compact('cotizacione', 'clientes', 'aseguradoras', 'tiposServicio', 'convenios'));
    }

    public function update(Request $request, Cotizacion $cotizacione)
    {
        if (!auth()->user()->isEmpleado() || auth()->user()->isOperador()) {
            abort(403);
        }

        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());

        $cotizacione->fill($data);
        $cotizacione->incluye_peajes = $request->boolean('incluye_peajes');
        $cotizacione = $this->calcularCostos($cotizacione);
        $cotizacione->save();

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización actualizada correctamente.');
    }

    public function destroy(Cotizacion $cotizacione)
    {
        if (!auth()->user()->isEmpleado() || auth()->user()->isOperador()) {
            abort(403);
        }

        $cotizacione->delete();
        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada.');
    }

    private function calcularCostos(Cotizacion $cotizacion): Cotizacion
    {
        $kmCobrar = $cotizacion->km_excedente > 0 ? $cotizacion->km_excedente : $cotizacion->distancia_km;
        $costoKilometraje = $kmCobrar * $cotizacion->costo_km;
        $subtotal = $cotizacion->costo_banderazo
            + $costoKilometraje
            + $cotizacion->costo_aprox_casetas;

        if ($cotizacion->convenio_aplicado_id) {
            $convenio = Convenio::find($cotizacion->convenio_aplicado_id);
            if ($convenio && $convenio->descuento > 0) {
                $cotizacion->costo_total = round($subtotal * (1 - $convenio->descuento / 100), 2);
                return $cotizacion;
            }
        }

        $cotizacion->costo_total = $subtotal;
        return $cotizacion;
    }

    private function generarFolio(): string
    {
        do {
            $folio = 'COT-' . strtoupper(Str::random(6));
        } while (Cotizacion::where('folio', $folio)->exists());

        return $folio;
    }
}
