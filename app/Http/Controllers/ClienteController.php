<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');

        $empresaId = session('empresa_id');
        $query = Cliente::where('empresa_id', $empresaId);

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('nombre', 'like', "%{$q}%")
                    ->orWhere('empresa', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('contacto', 'like', "%{$q}%");
            });
        }

        $clientes = $query->orderBy('nombre')
            ->paginate(15)
            ->through(function ($c) {
                $c->servicios_count = $c->cotizaciones()->count();
                $ultima = $c->cotizaciones()->latest()->first();
                $c->ultimo_servicio = $ultima?->created_at;
                return $c;
            });

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $this->authorize('empleado');
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'contacto' => 'nullable|string|max:255',
        ]);
        $data['empresa_id'] = session('empresa_id');
        Cliente::create($data);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $this->authorize('empleado');
        $cliente->load('cotizaciones');
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $this->authorize('empleado');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $this->authorize('empleado');
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'contacto' => 'nullable|string|max:255',
        ]);
        $cliente->update($data);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->authorize('empleado');
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}
