<?php

namespace App\Http\Controllers;

use App\Models\ServicioConfigurado;
use App\Models\Cliente;
use App\Models\TipoServicio;
use Illuminate\Http\Request;

class ServicioConfiguradoController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $configurados = ServicioConfigurado::where('empresa_id', session('empresa_id'))
            ->with('cliente', 'tipoServicio')
            ->orderBy('nombre')
            ->paginate(15);
        return view('servicios_configurados.index', compact('configurados'));
    }

    public function create()
    {
        $this->authorize('admin');
        $clientes = Cliente::where('empresa_id', session('empresa_id'))->orderBy('nombre')->get();
        $tipos = TipoServicio::orderBy('nombre')->get();
        return view('servicios_configurados.create', compact('clientes', 'tipos'));
    }

    protected function reglasValidacion(): array
    {
        return [
            'cliente_id' => ['required', 'exists:clientes,id'],
            'tipo_servicio_id' => ['required', 'exists:tipos_servicio,id'],
            'nombre' => ['required', 'string', 'max:200', 'regex:/^[\p{L}\p{N}\s\.\-\/]+$/u'],
            'tipo' => ['required', 'in:publico,aseguradora,particular'],
            'costo_banderazo' => ['required', 'numeric', 'min:0'],
            'costo_km' => ['required', 'numeric', 'min:0'],
            'activo' => ['boolean'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'cliente_id.required' => 'Selecciona un cliente.',
            'tipo_servicio_id.required' => 'Selecciona un tipo de servicio.',
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'tipo.required' => 'Selecciona el tipo de servicio.',
            'costo_banderazo.required' => 'El costo de banderazo es obligatorio.',
            'costo_km.required' => 'El costo por km es obligatorio.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['empresa_id'] = session('empresa_id');
        $data['activo'] ??= true;
        ServicioConfigurado::create($data);
        return redirect()->route('servicios-configurados.index')->with('success', 'Servicio configurado registrado correctamente.');
    }

    public function show(ServicioConfigurado $serviciosConfigurado)
    {
        $this->authorize('empleado');
        $serviciosConfigurado->load('cliente', 'tipoServicio');
        return view('servicios_configurados.show', compact('serviciosConfigurado'));
    }

    public function edit(ServicioConfigurado $serviciosConfigurado)
    {
        $this->authorize('admin');
        $clientes = Cliente::where('empresa_id', session('empresa_id'))->orderBy('nombre')->get();
        $tipos = TipoServicio::orderBy('nombre')->get();
        return view('servicios_configurados.edit', compact('serviciosConfigurado', 'clientes', 'tipos'));
    }

    public function update(Request $request, ServicioConfigurado $serviciosConfigurado)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['activo'] ??= false;
        $serviciosConfigurado->update($data);
        return redirect()->route('servicios-configurados.index')->with('success', 'Servicio configurado actualizado correctamente.');
    }

    public function destroy(ServicioConfigurado $serviciosConfigurado)
    {
        $this->authorize('admin');
        $serviciosConfigurado->delete();
        return redirect()->route('servicios-configurados.index')->with('success', 'Servicio configurado eliminado.');
    }
}
