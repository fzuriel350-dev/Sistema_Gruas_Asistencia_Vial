<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use App\Models\Cliente;
use App\Models\Aseguradora;
use Illuminate\Http\Request;

class ConvenioController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $convenios = Convenio::where('empresa_id', session('empresa_id'))
            ->with('cliente', 'aseguradora')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('convenios.index', compact('convenios'));
    }

    public function create()
    {
        $this->authorize('admin');
        $clientes = Cliente::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        $aseguradoras = Aseguradora::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return view('convenios.create', compact('clientes', 'aseguradoras'));
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'aseguradora_id' => 'required|exists:aseguradoras,id',
            'nombre' => 'required|string|max:150',
            'tipo' => 'required|in:local,foraneo',
            'costo_banderazo' => 'required|numeric|min:0',
            'costo_km' => 'required|numeric|min:0',
            'km_incluidos' => 'required|numeric|min:0',
            'descuento' => 'required|numeric|min:0|max:100',
            'cobertura' => 'required|string|max:255',
        ]);
        $data['empresa_id'] = session('empresa_id');
        Convenio::create($data);
        return redirect()->route('convenios.index')->with('success', 'Convenio creado correctamente.');
    }

    public function show(Convenio $convenio)
    {
        $this->authorize('empleado');
        $convenio->load('cliente', 'aseguradora');
        return view('convenios.show', compact('convenio'));
    }

    public function edit(Convenio $convenio)
    {
        $this->authorize('admin');
        $clientes = Cliente::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        $aseguradoras = Aseguradora::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return view('convenios.edit', compact('convenio', 'clientes', 'aseguradoras'));
    }

    public function update(Request $request, Convenio $convenio)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'aseguradora_id' => 'required|exists:aseguradoras,id',
            'nombre' => 'required|string|max:150',
            'tipo' => 'required|in:local,foraneo',
            'costo_banderazo' => 'required|numeric|min:0',
            'costo_km' => 'required|numeric|min:0',
            'km_incluidos' => 'required|numeric|min:0',
            'descuento' => 'required|numeric|min:0|max:100',
            'cobertura' => 'required|string|max:255',
        ]);
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
