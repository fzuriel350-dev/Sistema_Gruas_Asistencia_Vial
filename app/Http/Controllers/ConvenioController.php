<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use App\Models\Aseguradora;
use App\Models\TipoServicio;
use Illuminate\Http\Request;

class ConvenioController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $convenios = Convenio::where('empresa_id', session('empresa_id'))
            ->with('aseguradora', 'tipoServicio')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('convenios.index', compact('convenios'));
    }

    public function create()
    {
        $this->authorize('admin');
        $empresaId = session('empresa_id');
        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $tiposServicio = collect();
        return view('convenios.create', compact('aseguradoras', 'tiposServicio'));
    }

    public function tiposPorAseguradora(Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        $tipos = $aseguradora->tiposServicio()->get(['tipos_servicio.id', 'tipos_servicio.nombre']);
        return response()->json($tipos);
    }

    protected function reglasValidacion(): array
    {
        return [
            'aseguradora_id' => ['required', 'exists:aseguradoras,id'],
            'tipo_servicio_id' => ['required', 'exists:tipos_servicio,id'],
            'nombre' => ['required', 'string', 'max:150', 'regex:/^[\p{L}\p{N}\s\.\-\/]+$/u'],
            'tipo' => ['required', 'in:local,foraneo'],
            'costo_banderazo' => ['required', 'numeric', 'min:0'],
            'costo_km' => ['required', 'numeric', 'min:0'],
            'km_incluidos' => ['required', 'numeric', 'min:0'],
            'cubre_casetas_peaje' => ['required', 'in:0,1'],
            'descuento' => ['required', 'numeric', 'min:0', 'max:100'],
            'cobertura' => ['required', 'string', 'max:255'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'aseguradora_id.required' => 'Selecciona una aseguradora.',
            'tipo_servicio_id.required' => 'Selecciona un tipo de servicio.',
            'nombre.required' => 'El nombre del convenio es obligatorio.',
            'tipo.required' => 'Selecciona el tipo de convenio.',
            'costo_banderazo.required' => 'El costo de banderazo es obligatorio.',
            'costo_km.required' => 'El costo por km es obligatorio.',
            'km_incluidos.required' => 'Los km incluidos son obligatorios.',
            'descuento.max' => 'El descuento no puede ser mayor a 100.',
            'cobertura.required' => 'La cobertura es obligatoria.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['empresa_id'] = session('empresa_id');
        Convenio::create($data);
        return redirect()->route('convenios.index')->with('success', 'Convenio creado correctamente.');
    }

    public function show(Convenio $convenio)
    {
        $this->authorize('empleado');
        $convenio->load('aseguradora', 'tipoServicio');
        return view('convenios.show', compact('convenio'));
    }

    public function edit(Convenio $convenio)
    {
        $this->authorize('admin');
        $empresaId = session('empresa_id');
        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $tiposServicio = $convenio->aseguradora
            ? $convenio->aseguradora->tiposServicio()->get()
            : collect();
        return view('convenios.edit', compact('convenio', 'aseguradoras', 'tiposServicio'));
    }

    public function update(Request $request, Convenio $convenio)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $convenio->update($data);
        return redirect()->route('convenios.index')->with('success', 'Convenio actualizado correctamente.');
    }

    public function destroy(Convenio $convenio)
    {
        $this->authorize('admin');
        $convenio->delete();
        return redirect()->route('convenios.index')->with('success', 'Convenio eliminado.');
    }
}
