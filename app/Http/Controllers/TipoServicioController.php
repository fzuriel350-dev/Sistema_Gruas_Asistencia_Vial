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
        $this->authorize('empleado');
        return view('tipos_servicio.create');
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        $data['empresa_id'] = session('empresa_id');
        TipoServicio::create($data);
        return redirect()->route('tipos-servicio.index')->with('success', 'Tipo de servicio creado correctamente.');
    }

    public function show(TipoServicio $tiposServicio)
    {
        $this->authorize('empleado');
        $tiposServicio->loadCount('cotizaciones', 'serviciosConfigurados');
        return view('tipos_servicio.show', compact('tiposServicio'));
    }

    public function edit(TipoServicio $tiposServicio)
    {
        $this->authorize('empleado');
        return view('tipos_servicio.edit', compact('tiposServicio'));
    }

    public function update(Request $request, TipoServicio $tiposServicio)
    {
        $this->authorize('empleado');
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        $tiposServicio->update($data);
        return redirect()->route('tipos-servicio.index')->with('success', 'Tipo de servicio actualizado correctamente.');
    }

    public function destroy(TipoServicio $tiposServicio)
    {
        $this->authorize('empleado');
        $tiposServicio->delete();
        return redirect()->route('tipos-servicio.index')->with('success', 'Tipo de servicio eliminado.');
    }
}
