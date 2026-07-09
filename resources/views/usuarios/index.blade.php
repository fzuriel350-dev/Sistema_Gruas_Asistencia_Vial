@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="max-w-7xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3>Usuarios</h3>
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <input type="text" name="q" placeholder="Buscar por nombre o email..." value="{{ request('q') }}" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg w-56">
                    <select name="rol" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg" onchange="this.form.submit()">
                        <option value="">Todos los roles</option>
                        <option value="admin" @selected(request('rol') === 'admin')>Admin</option>
                        <option value="cotizador" @selected(request('rol') === 'cotizador')>Cotizador</option>
                        <option value="operador" @selected(request('rol') === 'operador')>Operador</option>
                        <option value="cliente" @selected(request('rol') === 'cliente')>Cliente</option>
                    </select>
                    @if (request('q') || request('rol'))
                        <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-ghost">Limpiar</a>
                    @endif
                </form>
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Empleado vinculado</th>
                        <th>Registrado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $u)
                    <tr>
                        <td class="text-gray-400 text-xs">{{ $u->id }}</td>
                        <td><strong>{{ $u->name }}</strong></td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="status status-{{ $u->role === 'admin' ? 'active' : ($u->role === 'cliente' ? 'pending' : 'success') }}">
                                <span class="status-dot"></span> {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>{{ $u->empleado?->nombreCompleto() ?: '—' }}</td>
                        <td class="text-sm text-gray-500">{{ $u->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-primary">Editar</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-8">No hay usuarios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection
