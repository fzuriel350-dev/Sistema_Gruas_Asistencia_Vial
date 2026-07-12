<?php

namespace App\Http\Controllers;

use App\Models\TipoServicio;
use Illuminate\Http\Request;

class TipoServicioController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $tipos = TipoServicio::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->paginate(15);
        return view('tipos_servicio.index', compact('tipos'));
    }

    public function create()
    {
        $this->authorize('admin');
        return view('tipos_servicio.create');
    }

    protected function reglasValidacion(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\s]+$/u'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'nombre.required' => 'El nombre del tipo de servicio es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, números y espacios.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['empresa_id'] = session('empresa_id');
        TipoServicio::create($data);
        return redirect()->route('tipos-servicio.index')->with('success', 'Tipo de servicio creado correctamente.');
    }

    public function show(TipoServicio $tiposServicio)
    {
        $this->authorize('empleado');
        $tiposServicio->loadCount('cotizaciones');
        return view('tipos_servicio.show', compact('tiposServicio'));
    }

    public function edit(TipoServicio $tiposServicio)
    {
        $this->authorize('admin');
        return view('tipos_servicio.edit', compact('tiposServicio'));
    }

    public function update(Request $request, TipoServicio $tiposServicio)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $tiposServicio->update($data);
        return redirect()->route('tipos-servicio.index')->with('success', 'Tipo de servicio actualizado correctamente.');
    }

    public function destroy(TipoServicio $tiposServicio)
    {
        $this->authorize('admin');
        $tiposServicio->delete();
        return redirect()->route('tipos-servicio.index')->with('success', 'Tipo de servicio eliminado.');
    }
}
