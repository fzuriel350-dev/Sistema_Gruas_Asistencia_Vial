<?php

namespace App\Http\Controllers;

use App\Models\Cotizador;
use App\Models\Empleado;
use App\Models\Operador;
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
            ->with('usuario', 'oficina')
            ->orderBy('nombre')
            ->paginate(15);
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $this->authorize('admin');
        $oficinas = \App\Models\Oficina::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return view('empleados.create', compact('oficinas'));
    }

    protected function reglasBase(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'apellido_paterno' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'apellido_materno' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'oficina_id' => ['nullable', 'exists:oficinas,id'],
            'puesto' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,cotizador,operador'],
            'licencia_tipo' => ['nullable', 'string', 'in:A,B,C,D'],
            'licencia_año_vencimiento' => ['nullable', 'date'],
            'licencia_vencimiento_federal' => ['nullable', 'date'],
            'zona_cobertura' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function mensajesBase(): array
    {
        return [
            'nombre.required' => 'El nombre del empleado es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras y espacios.',
            'apellido_materno.regex' => 'El apellido materno solo puede contener letras y espacios.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y signo +.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'role.required' => 'Selecciona un rol para el empleado.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate($this->reglasBase(), $this->mensajesBase());

        $empresaId = session('empresa_id');

        $empleado = Empleado::create([
            'empresa_id' => $empresaId,
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'] ?? '',
            'telefono' => $data['telefono'] ?? '',
            'direccion' => $data['direccion'] ?? '',
            'oficina_id' => $data['oficina_id'] ?? null,
            'puesto' => $data['puesto'] ?? '',
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
            Operador::create([
                'empresa_id' => $empresaId,
                'empleado_id' => $empleado->id,
                'licencia_tipo' => $data['licencia_tipo'] ?? null,
                'licencia_año_vencimiento' => $data['licencia_año_vencimiento'] ?? null,
                'licencia_vencimiento_federal' => $data['licencia_vencimiento_federal'] ?? null,
                'disponible' => true,
            ]);
        }

        if ($data['role'] === 'cotizador') {
            Cotizador::create([
                'empresa_id' => $empresaId,
                'empleado_id' => $empleado->id,
                'zona_cobertura' => $data['zona_cobertura'] ?? null,
                'activo' => true,
            ]);
        }

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function show(Empleado $empleado)
    {
        $this->authorize('admin');
        $empleado->load('usuario', 'operador', 'cotizador', 'oficina');
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        $this->authorize('admin');
        $empleado->load('usuario', 'operador', 'cotizador');
        $oficinas = \App\Models\Oficina::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return view('empleados.edit', compact('empleado', 'oficinas'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $this->authorize('admin');
        $reglas = $this->reglasBase();
        $reglas['email'] = ['required', 'email', 'max:255', 'unique:users,email,' . $empleado->usuario?->id];
        $reglas['password'] = ['nullable', 'confirmed', Rules\Password::defaults()];
        $data = $request->validate($reglas, $this->mensajesBase());

        $empleado->update([
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'] ?? '',
            'telefono' => $data['telefono'] ?? '',
            'direccion' => $data['direccion'] ?? '',
            'oficina_id' => $data['oficina_id'] ?? null,
            'puesto' => $data['puesto'] ?? '',
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

        if ($data['role'] === 'operador') {
            $opData = [
                'licencia_tipo' => $data['licencia_tipo'] ?? null,
                'licencia_año_vencimiento' => $data['licencia_año_vencimiento'] ?? null,
                'licencia_vencimiento_federal' => $data['licencia_vencimiento_federal'] ?? null,
            ];
            if ($empleado->operador) {
                $empleado->operador->update($opData);
            } else {
                Operador::create(array_merge($opData, [
                    'empresa_id' => session('empresa_id'),
                    'empleado_id' => $empleado->id,
                    'disponible' => true,
                ]));
            }
        } elseif ($empleado->operador) {
            $empleado->operador->update([
                'licencia_tipo' => null,
                'licencia_año_vencimiento' => null,
                'licencia_vencimiento_federal' => null,
            ]);
        }

        if ($data['role'] === 'cotizador') {
            $cotData = [
                'zona_cobertura' => $data['zona_cobertura'] ?? null,
            ];
            if ($empleado->cotizador) {
                $empleado->cotizador->update($cotData);
            } else {
                Cotizador::create(array_merge($cotData, [
                    'empresa_id' => session('empresa_id'),
                    'empleado_id' => $empleado->id,
                    'activo' => true,
                ]));
            }
        } elseif ($empleado->cotizador) {
            $empleado->cotizador->update([
                'zona_cobertura' => null,
            ]);
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
        if ($empleado->cotizador) {
            $empleado->cotizador->delete();
        }
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }
}
