<?php

namespace App\Http\Controllers;

use App\Models\Oficina;
use Illuminate\Http\Request;

class OficinaController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $oficinas = Oficina::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->paginate(15);
        return view('oficinas.index', compact('oficinas'));
    }

    public function create()
    {
        $this->authorize('admin');
        return view('oficinas.create');
    }

    protected function reglasValidacion(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'ciudad' => ['nullable', 'string', 'max:60', 'regex:/^[\p{L}\s]+$/u'],
            'estado' => ['nullable', 'string', 'max:45', 'regex:/^[\p{L}\s]+$/u'],
            'telefono' => ['nullable', 'string', 'max:25', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'encargado' => ['nullable', 'string', 'max:150', 'regex:/^[\p{L}\s]+$/u'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'nombre.required' => 'El nombre de la oficina es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'ciudad.regex' => 'La ciudad solo puede contener letras y espacios.',
            'estado.regex' => 'El estado solo puede contener letras y espacios.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y signo +.',
            'encargado.regex' => 'El nombre del encargado solo puede contener letras y espacios.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['empresa_id'] = session('empresa_id');
        Oficina::create($data);
        return redirect()->route('oficinas.index')->with('success', 'Oficina registrada correctamente.');
    }

    public function show(Oficina $oficina)
    {
        $this->authorize('empleado');
        return view('oficinas.show', compact('oficina'));
    }

    public function edit(Oficina $oficina)
    {
        $this->authorize('admin');
        return view('oficinas.edit', compact('oficina'));
    }

    public function update(Request $request, Oficina $oficina)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $oficina->update($data);
        return redirect()->route('oficinas.index')->with('success', 'Oficina actualizada correctamente.');
    }

    public function destroy(Oficina $oficina)
    {
        $this->authorize('admin');
        $oficina->delete();
        return redirect()->route('oficinas.index')->with('success', 'Oficina eliminada.');
    }
}
