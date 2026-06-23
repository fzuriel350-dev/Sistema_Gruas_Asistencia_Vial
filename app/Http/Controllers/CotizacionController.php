<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Convenio;
use App\Models\Cliente;
use App\Models\Aseguradora;
use App\Models\TipoServicio;
use App\Models\Empresa;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $query = Cotizacion::where('empresa_id', $empresaId)
            ->with('cliente', 'aseguradora', 'tipoServicio');

        if (request('aseguradora_id')) {
            $query->where('aseguradora_id', request('aseguradora_id'));
        }

        if (request('estatus')) {
            $query->where('estatus', request('estatus'));
        }

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('folio', 'like', "%{$q}%")
                    ->orWhere('origen', 'like', "%{$q}%")
                    ->orWhere('destino', 'like', "%{$q}%")
                    ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$q}%"));
            });
        }

        $cotizaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->orderBy('nombre')->get();

        return view('cotizaciones.index', compact('cotizaciones', 'aseguradoras'));
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'aseguradora_id' => 'required|exists:aseguradoras,id',
            'tipo_servicio_id' => 'required|exists:tipos_servicio,id',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'color' => 'nullable|string',
            'placas' => 'required|string',
            'no_poliza' => 'nullable|string',
            'origen' => 'required|string',
            'destino' => 'required|string',
            'distancia_km' => 'required|numeric|min:0',
            'tiempo_estimado' => 'required|integer|min:0',
            'tipo_ruta' => 'required|in:local,foraneo',
            'con_peaje' => 'boolean',
            'num_casetas' => 'integer|min:0',
            'costo_casetas' => 'numeric|min:0',
            'extras' => 'numeric|min:0',
            'costo_banderazo' => 'required|numeric|min:0',
            'costo_km' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
            'action' => 'required|in:draft,generate',
        ]);

        $data['empresa_id'] = session('empresa_id');
        $data['con_peaje'] = $request->boolean('con_peaje');
        $data['num_casetas'] = $request->input('num_casetas', 0);
        $data['costo_casetas'] = $request->input('costo_casetas', 0);
        $data['extras'] = $request->input('extras', 0);
        $data['created_by'] = auth()->id();
        $data['folio'] = $this->generarFolio();
        $data['km_excedente'] = 0;

        $cotizacion = new Cotizacion($data);
        $cotizacion = $this->calcularCostos($cotizacion);
        $cotizacion->estatus = $data['action'] === 'generate' ? 'pendiente' : 'borrador';

        if ($request->filled('convenio_id')) {
            $cotizacion->convenio_id = $request->convenio_id;
            $convenio = Convenio::find($request->convenio_id);
            if ($convenio) {
                $cotizacion->descuento_porcentaje = $convenio->descuento ?? 0;
                $cotizacion->cobertura = $convenio->cobertura ?? 'sin_cobertura';
            }
        }

        $cotizacion->save();

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización ' . ($data['action'] === 'generate' ? 'generada' : 'guardada') . ' correctamente.');
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
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'aseguradora_id' => 'required|exists:aseguradoras,id',
            'tipo_servicio_id' => 'required|exists:tipos_servicio,id',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'color' => 'nullable|string',
            'placas' => 'required|string',
            'no_poliza' => 'nullable|string',
            'origen' => 'required|string',
            'destino' => 'required|string',
            'distancia_km' => 'required|numeric|min:0',
            'tiempo_estimado' => 'required|integer|min:0',
            'tipo_ruta' => 'required|in:local,foraneo',
            'con_peaje' => 'boolean',
            'num_casetas' => 'integer|min:0',
            'costo_casetas' => 'numeric|min:0',
            'extras' => 'numeric|min:0',
            'costo_banderazo' => 'required|numeric|min:0',
            'costo_km' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
            'action' => 'required|in:draft,generate',
        ]);

        $cotizacione->fill($data);
        $cotizacione->con_peaje = $request->boolean('con_peaje');
        $cotizacione = $this->calcularCostos($cotizacione);

        if ($data['action'] === 'generate' && $cotizacione->estatus === 'borrador') {
            $cotizacione->estatus = 'pendiente';
        }

        if ($request->filled('convenio_id')) {
            $cotizacione->convenio_id = $request->convenio_id;
            $convenio = Convenio::find($request->convenio_id);
            if ($convenio) {
                $cotizacione->descuento_porcentaje = $convenio->descuento ?? 0;
                $cotizacione->cobertura = $convenio->cobertura ?? 'sin_cobertura';
            }
        } else {
            $cotizacione->convenio_id = null;
            $cotizacione->descuento_porcentaje = 0;
        }

        $cotizacione->save();

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización actualizada correctamente.');
    }

    public function destroy(Cotizacion $cotizacione)
    {
        $cotizacione->delete();
        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada.');
    }

    private function calcularCostos(Cotizacion $cotizacion): Cotizacion
    {
        $cotizacion->costo_kilometraje = $cotizacion->distancia_km * $cotizacion->costo_km;
        $cotizacion->subtotal = $cotizacion->costo_banderazo
            + $cotizacion->costo_kilometraje
            + $cotizacion->costo_casetas
            + $cotizacion->extras;

        $descuento = $cotizacion->descuento_porcentaje > 0
            ? $cotizacion->subtotal * ($cotizacion->descuento_porcentaje / 100)
            : 0;
        $cotizacion->descuento_monto = $descuento;

        $baseIva = $cotizacion->subtotal - $descuento;
        $cotizacion->iva = round($baseIva * 0.16, 2);
        $cotizacion->costo_total = $baseIva + $cotizacion->iva;

        return $cotizacion;
    }

    private function generarFolio(): string
    {
        $ultimo = Cotizacion::orderBy('id', 'desc')->first();
        $numero = $ultimo ? intval(substr($ultimo->folio, 4)) + 1 : 1;
        return 'COT-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
