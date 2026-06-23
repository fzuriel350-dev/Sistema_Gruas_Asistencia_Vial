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

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'licencia_tipo' => 'required|string|max:50',
            'licencia_año_vencimiento' => 'required|date',
            'disponible' => 'boolean',
        ]);
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
        $data = $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'licencia_tipo' => 'required|string|max:50',
            'licencia_año_vencimiento' => 'required|date',
            'disponible' => 'boolean',
        ]);
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
