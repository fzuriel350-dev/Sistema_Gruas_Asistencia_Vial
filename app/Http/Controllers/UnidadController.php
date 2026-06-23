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
            ->with('operador.empleado')
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
        return view('unidades.create', compact('operadores'));
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate([
            'marca' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'ano' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'placas' => 'required|string|max:20',
            'numero_serie' => 'nullable|string|max:100',
            'seguro_vencimiento' => 'nullable|date',
            'operador_id' => 'nullable|exists:operadores,id',
        ]);
        $data['empresa_id'] = session('empresa_id');
        Unidad::create($data);
        return redirect()->route('unidades.index')->with('success', 'Unidad registrada correctamente.');
    }

    public function show(Unidad $unidad)
    {
        $this->authorize('empleado');
        $unidad->load('operador.empleado');
        return view('unidades.show', compact('unidad'));
    }

    public function edit(Unidad $unidad)
    {
        $this->authorize('empleado');
        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado')
            ->orderBy('id')
            ->get();
        return view('unidades.edit', compact('unidad', 'operadores'));
    }

    public function update(Request $request, Unidad $unidad)
    {
        $this->authorize('empleado');
        $data = $request->validate([
            'marca' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'ano' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'placas' => 'required|string|max:20',
            'numero_serie' => 'nullable|string|max:100',
            'seguro_vencimiento' => 'nullable|date',
            'operador_id' => 'nullable|exists:operadores,id',
        ]);
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
