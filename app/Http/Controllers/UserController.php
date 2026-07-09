<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Operador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authorize('admin');
            return $next($request);
        });
    }

    public function index()
    {
        $empresaId = session('empresa_id');

        $query = User::where('empresa_id', $empresaId)
            ->with('empleado')
            ->orderBy('created_at', 'desc');

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($rol = request('rol')) {
            $query->where('role', $rol);
        }

        $usuarios = $query->paginate(15);

        return view('usuarios.index', compact('usuarios'));
    }

    public function edit(User $usuario)
    {
        $empresaId = session('empresa_id');

        if ($usuario->empresa_id !== $empresaId) {
            abort(403);
        }

        $empleados = Empleado::where('empresa_id', $empresaId)
            ->whereDoesntHave('usuario')
            ->orWhere('id', $usuario->empleado_id)
            ->orderBy('nombre')
            ->get();

        return view('usuarios.edit', compact('usuario', 'empleados'));
    }

    public function update(Request $request, User $usuario)
    {
        $empresaId = session('empresa_id');

        if ($usuario->empresa_id !== $empresaId) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'role' => ['required', 'in:admin,cotizador,operador,cliente'],
            'empleado_id' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'emp_nombre' => ['nullable', 'required_if:empleado_id,__nuevo__', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'emp_apellido_paterno' => ['nullable', 'required_if:empleado_id,__nuevo__', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'emp_apellido_materno' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'emp_telefono' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'emp_puesto' => ['nullable', 'string', 'max:255'],
            'emp_direccion' => ['nullable', 'string', 'max:500'],
        ]);

        $isEmployee = in_array($data['role'], ['admin', 'cotizador', 'operador']);

        if ($isEmployee) {
            if ($data['empleado_id'] === '__nuevo__') {
                $empleado = Empleado::create([
                    'empresa_id' => $empresaId,
                    'nombre' => $data['emp_nombre'],
                    'apellido_paterno' => $data['emp_apellido_paterno'],
                    'apellido_materno' => $data['emp_apellido_materno'] ?? '',
                    'telefono' => $data['emp_telefono'] ?? '',
                    'puesto' => $data['emp_puesto'] ?? '',
                    'direccion' => $data['emp_direccion'] ?? '',
                ]);
                $data['empleado_id'] = $empleado->id;
            } elseif (!$data['empleado_id']) {
                return back()->withErrors(['empleado_id' => 'Debes seleccionar o crear un empleado para roles administrativos.'])->withInput();
            } else {
                $data['empleado_id'] = (int) $data['empleado_id'];
            }
        } else {
            $data['empleado_id'] = null;
        }

        $oldRole = $usuario->role;
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'empleado_id' => $data['empleado_id'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $usuario->update($updateData);

        if ($data['role'] === 'operador' && $oldRole !== 'operador') {
            Operador::firstOrCreate([
                'empresa_id' => $empresaId,
                'empleado_id' => $data['empleado_id'],
            ], ['disponible' => true]);
        }

        if ($data['role'] !== 'operador' && $oldRole === 'operador') {
            Operador::where('empleado_id', $usuario->empleado_id)->delete();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }
}
