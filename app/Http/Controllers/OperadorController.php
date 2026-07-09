<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use App\Models\Empleado;
use Illuminate\Http\Request;

class OperadorController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado', 'unidades')
            ->orderBy('id')
            ->paginate(15);
        return view('operadores.index', compact('operadores'));
    }

    public function create()
    {
        $this->authorize('empleado');
        $empleados = Empleado::where('empresa_id', session('empresa_id'))
            ->whereDoesntHave('operador')
            ->orderBy('nombre')
            ->get();
        return view('operadores.create', compact('empleados'));
    }

    protected function reglasValidacion(): array
    {
        return [
            'empleado_id' => ['required', 'exists:empleados,id'],
            'licencia_tipo' => ['required', 'string', 'max:50', 'regex:/^[\p{L}\p{N}\s\-]+$/u'],
            'licencia_año_vencimiento' => ['required', 'date'],
            'licencia_vencimiento_federal' => ['nullable', 'date'],
            'disponible' => ['boolean'],
            'puntos_acumulados' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'empleado_id.required' => 'Selecciona un empleado.',
            'licencia_tipo.required' => 'El tipo de licencia es obligatorio.',
            'licencia_año_vencimiento.required' => 'La fecha de vencimiento de la licencia es obligatoria.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['empresa_id'] = session('empresa_id');
        $data['disponible'] = $request->boolean('disponible');
        Operador::create($data);
        return redirect()->route('operadores.index')->with('success', 'Operador registrado correctamente.');
    }

    public function show(Operador $operador)
    {
        $this->authorize('empleado');
        $operador->load('empleado', 'unidades');
        return view('operadores.show', compact('operador'));
    }

    public function edit(Operador $operador)
    {
        $this->authorize('empleado');
        $empleados = Empleado::where('empresa_id', session('empresa_id'))
            ->where(function ($q) use ($operador) {
                $q->whereDoesntHave('operador')->orWhere('id', $operador->empleado_id);
            })
            ->orderBy('nombre')
            ->get();
        return view('operadores.edit', compact('operador', 'empleados'));
    }

    public function update(Request $request, Operador $operador)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['disponible'] = $request->boolean('disponible');
        $operador->update($data);
        return redirect()->route('operadores.index')->with('success', 'Operador actualizado correctamente.');
    }

    public function destroy(Operador $operador)
    {
        $this->authorize('empleado');
        $operador->delete();
        return redirect()->route('operadores.index')->with('success', 'Operador eliminado.');
    }
}
