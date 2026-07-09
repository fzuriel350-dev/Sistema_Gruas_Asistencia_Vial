<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authorize('empleado');
            return $next($request);
        });
    }

    public function index()
    {
        $empresaId = session('empresa_id');

        $query = Cliente::where('empresa_id', $empresaId)
            ->with('aseguradora')
            ->withCount('servicios');

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('nombre', 'like', "%{$q}%")
                    ->orWhere('empresa', 'like', "%{$q}%")
                    ->orWhere('contacto', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        $clientes = $query->orderBy('nombre')->paginate(15);

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $aseguradoras = \App\Models\Aseguradora::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return view('clientes.create', compact('aseguradoras'));
    }

    protected function reglasValidacion(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'empresa' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'contacto' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'aseguradora_id' => ['nullable', 'exists:aseguradoras,id'],
            'numero_poliza' => ['nullable', 'string', 'max:50'],
            'tipo_cobertura_poliza' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'empresa.regex' => 'El nombre de la empresa solo puede contener letras y espacios.',
            'contacto.regex' => 'El contacto solo puede contener letras y espacios.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y signo +.',
            'numero_poliza.regex' => 'El número de póliza solo puede contener números, guiones, paréntesis y signo +.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'aseguradora_id.exists' => 'La aseguradora seleccionada no es válida.',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());

        $data['empresa_id'] = session('empresa_id');
        Cliente::create($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('cotizaciones', 'convenios', 'aseguradora');
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $aseguradoras = \App\Models\Aseguradora::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return view('clientes.edit', compact('cliente', 'aseguradoras'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());

        $cliente->update($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}
