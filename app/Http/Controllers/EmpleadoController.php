<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmpleadoController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $empleados = Empleado::where('empresa_id', session('empresa_id'))
            ->with('usuario')
            ->orderBy('nombre')
            ->paginate(15);
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $this->authorize('admin');
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,cotizador,operador',
        ]);

        $empresaId = session('empresa_id');

        $empleado = Empleado::create([
            'empresa_id' => $empresaId,
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'] ?? '',
            'telefono' => $data['telefono'] ?? '',
            'direccion' => $data['direccion'] ?? '',
        ]);

        $user = User::create([
            'name' => $data['nombre'] . ' ' . $data['apellido_paterno'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'empresa_id' => $empresaId,
            'empleado_id' => $empleado->id,
        ]);

        if ($data['role'] === 'operador') {
            \App\Models\Operador::create([
                'empresa_id' => $empresaId,
                'empleado_id' => $empleado->id,
                'disponible' => true,
            ]);
        }

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function show(Empleado $empleado)
    {
        $this->authorize('admin');
        $empleado->load('usuario', 'operador');
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        $this->authorize('admin');
        $empleado->load('usuario');
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . $empleado->usuario?->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,cotizador,operador',
        ]);

        $empleado->update([
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'] ?? '',
            'telefono' => $data['telefono'] ?? '',
            'direccion' => $data['direccion'] ?? '',
        ]);

        if ($empleado->usuario) {
            $userData = [
                'name' => $data['nombre'] . ' ' . $data['apellido_paterno'],
                'email' => $data['email'],
                'role' => $data['role'],
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($data['password']);
            }
            $empleado->usuario->update($userData);
        }

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $this->authorize('admin');
        if ($empleado->usuario) {
            $empleado->usuario->delete();
        }
        if ($empleado->operador) {
            $empleado->operador->delete();
        }
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }
}
