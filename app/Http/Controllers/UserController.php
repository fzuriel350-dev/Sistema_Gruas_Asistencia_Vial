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
        ]);

        $isEmployee = in_array($data['role'], ['admin', 'cotizador', 'operador']);

        if ($isEmployee) {
            if (!$data['empleado_id']) {
                return back()->withErrors(['empleado_id' => 'Debes seleccionar un empleado para este rol.'])->withInput();
            }
            $data['empleado_id'] = (int) $data['empleado_id'];
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

    public function destroy(User $usuario)
    {
        $empresaId = session('empresa_id');

        if ($usuario->empresa_id !== $empresaId) {
            abort(403);
        }

        if ($usuario->id === auth()->id()) {
            abort(403, 'No puedes eliminar tu propia cuenta.');
        }

        $empleado = $usuario->empleado;

        $usuario->delete();

        if ($empleado && !$empleado->usuario) {
            if ($empleado->operador) {
                $empleado->operador->delete();
            }
            if ($empleado->cotizador) {
                $empleado->cotizador->delete();
            }
            $empleado->delete();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
