<?php

namespace App\Http\Controllers;

use App\Models\Aseguradora;
use Illuminate\Http\Request;

class AseguradoraController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $aseguradoras = Aseguradora::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->paginate(15);
        return view('aseguradoras.index', compact('aseguradoras'));
    }

    public function create()
    {
        $this->authorize('empleado');
        return view('aseguradoras.create');
    }

    protected function reglasValidacion(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'nombre.required' => 'El nombre de la aseguradora es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y signo +.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['empresa_id'] = session('empresa_id');
        Aseguradora::create($data);
        return redirect()->route('aseguradoras.index')->with('success', 'Aseguradora creada correctamente.');
    }

    public function show(Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        $aseguradora->loadCount('convenios', 'cotizaciones');
        return view('aseguradoras.show', compact('aseguradora'));
    }

    public function edit(Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        return view('aseguradoras.edit', compact('aseguradora'));
    }

    public function update(Request $request, Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $aseguradora->update($data);
        return redirect()->route('aseguradoras.index')->with('success', 'Aseguradora actualizada correctamente.');
    }

    public function destroy(Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        $aseguradora->delete();
        return redirect()->route('aseguradoras.index')->with('success', 'Aseguradora eliminada.');
    }
}
