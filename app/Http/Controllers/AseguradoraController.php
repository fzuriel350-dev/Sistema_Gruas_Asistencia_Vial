<?php

namespace App\Http\Controllers;

use App\Models\Aseguradora;
use App\Models\TipoServicio;
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
        $tiposServicio = TipoServicio::where('empresa_id', session('empresa_id'))->orderBy('nombre')->get();
        return view('aseguradoras.create', compact('tiposServicio'));
    }

    protected function reglasValidacion(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'tipos_servicio' => ['required', 'array', 'min:1'],
            'tipos_servicio.*' => ['exists:tipos_servicio,id'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'nombre.required' => 'El nombre de la aseguradora es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y signo +.',
            'tipos_servicio.required' => 'Selecciona al menos un tipo de servicio.',
            'tipos_servicio.min' => 'Selecciona al menos un tipo de servicio.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $empresaId = session('empresa_id');

        $aseguradora = Aseguradora::create([
            'empresa_id' => $empresaId,
            'nombre' => $data['nombre'],
            'telefono' => $data['telefono'] ?? null,
        ]);

        $aseguradora->tiposServicio()->sync($data['tipos_servicio']);

        return redirect()->route('aseguradoras.index')->with('success', 'Aseguradora creada correctamente.');
    }

    public function show(Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        $aseguradora->load('tiposServicio');
        $aseguradora->loadCount('convenios', 'cotizaciones');
        return view('aseguradoras.show', compact('aseguradora'));
    }

    public function edit(Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        $aseguradora->load('tiposServicio');
        $tiposServicio = TipoServicio::where('empresa_id', session('empresa_id'))->orderBy('nombre')->get();
        return view('aseguradoras.edit', compact('aseguradora', 'tiposServicio'));
    }

    public function update(Request $request, Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $aseguradora->update([
            'nombre' => $data['nombre'],
            'telefono' => $data['telefono'] ?? null,
        ]);
        $aseguradora->tiposServicio()->sync($data['tipos_servicio']);
        return redirect()->route('aseguradoras.index')->with('success', 'Aseguradora actualizada correctamente.');
    }

    public function destroy(Aseguradora $aseguradora)
    {
        $this->authorize('empleado');
        $aseguradora->delete();
        return redirect()->route('aseguradoras.index')->with('success', 'Aseguradora eliminada.');
    }
}
