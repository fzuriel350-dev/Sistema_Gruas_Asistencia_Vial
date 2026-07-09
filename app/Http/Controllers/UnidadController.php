<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use App\Models\Operador;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $unidades = Unidad::where('empresa_id', session('empresa_id'))
            ->with('operador.empleado', 'oficina')
            ->orderBy('marca')
            ->paginate(15);
        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        $this->authorize('empleado');
        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado')
            ->orderBy('id')
            ->get();
        $oficinas = \App\Models\Oficina::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return view('unidades.create', compact('operadores', 'oficinas'));
    }

    protected function reglasValidacion(): array
    {
        return [
            'marca' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\s\.\-\/]+$/u'],
            'tipo' => ['required', 'string', 'max:100'],
            'año' => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'modelo' => ['nullable', 'string', 'max:100', 'regex:/^[\p{L}\p{N}\s\.\-\/]+$/u'],
            'placas' => ['required', 'string', 'max:20', 'regex:/^[\p{L}\p{N}\-\s]+$/u'],
            'numero_economico' => ['nullable', 'string', 'max:50'],
            'numero_serie' => ['nullable', 'string', 'max:100'],
            'estado_emplacado' => ['nullable', 'string', 'max:100', 'regex:/^[\p{L}\s]+$/u'],
            'seguro_vencimiento' => ['nullable', 'date'],
            'oficina_id' => ['nullable', 'exists:oficinas,id'],
            'operador_id' => ['nullable', 'exists:operadores,id'],
            'activo' => ['boolean'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'marca.required' => 'La marca es obligatoria.',
            'marca.regex' => 'La marca contiene caracteres no válidos.',
            'año.required' => 'El año es obligatorio.',
            'año.min' => 'El año debe ser 1990 o posterior.',
            'placas.required' => 'Las placas son obligatorias.',
            'placas.regex' => 'Las placas solo pueden contener letras, números y guiones.',
            'estado_emplacado.regex' => 'El estado solo puede contener letras y espacios.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['empresa_id'] = session('empresa_id');
        $data['activo'] = $request->boolean('activo');
        Unidad::create($data);
        return redirect()->route('unidades.index')->with('success', 'Unidad registrada correctamente.');
    }

    public function show(Unidad $unidad)
    {
        $this->authorize('empleado');
        $unidad->load('operador.empleado', 'oficina');
        return view('unidades.show', compact('unidad'));
    }

    public function edit(Unidad $unidad)
    {
        $this->authorize('empleado');
        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado')
            ->orderBy('id')
            ->get();
        $oficinas = \App\Models\Oficina::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return view('unidades.edit', compact('unidad', 'operadores', 'oficinas'));
    }

    public function update(Request $request, Unidad $unidad)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['activo'] = $request->boolean('activo');
        $unidad->update($data);
        return redirect()->route('unidades.index')->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy(Unidad $unidad)
    {
        $this->authorize('empleado');
        $unidad->delete();
        return redirect()->route('unidades.index')->with('success', 'Unidad eliminada.');
    }
}
