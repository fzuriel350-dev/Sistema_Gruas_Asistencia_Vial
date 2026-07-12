@extends('layouts.app')
@section('title', 'Editar Usuario')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3>Editar Usuario</h3>
            <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-ghost">Volver</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="form-grid">
                @csrf @method('PUT')

                <div class="col-span-full">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-md bg-[var(--geg-yellow)]/15 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[var(--geg-yellow)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Rol y Acceso</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $usuario->name) }}" required>
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}" required>
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="form-group col-span-full">
                    <label for="role">Rol</label>
                    <select id="role" name="role" required onchange="cambiarRol(this.value)">
                        <option value="">Seleccionar rol...</option>
                        <option value="admin" @selected(old('role', $usuario->role) === 'admin')>Administrador</option>
                        <option value="cotizador" @selected(old('role', $usuario->role) === 'cotizador')>Cotizador</option>
                        <option value="operador" @selected(old('role', $usuario->role) === 'operador')>Operador</option>
                        <option value="cliente" @selected(old('role', $usuario->role) === 'cliente')>Cliente</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" />
                </div>

                {{-- ═══════ EMPLEADO VINCULADO (no cliente) ═══════ --}}
                <div id="seccionEmpleado" style="display:none">
                    <hr class="border-gray-200 my-1 col-span-full">
                    <div class="col-span-full">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-md bg-purple-500/15 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Empleado vinculado</span>
                        </div>
                    </div>

                    <div class="form-group col-span-full">
                        <label for="empleado_id">Seleccionar empleado</label>
                        <select id="empleado_id" name="empleado_id">
                            <option value="">Sin empleado</option>
                            @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}" @selected(old('empleado_id', $usuario->empleado_id) == $empleado->id)>{{ $empleado->nombreCompleto() }} {{ $empleado->puesto ? '(' . $empleado->puesto . ')' : '' }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Si el empleado no existe, créalo desde <a href="{{ route('empleados.create') }}" class="text-[var(--geg-yellow)] hover:underline font-medium">Empleados → Nuevo</a></p>
                        <x-input-error :messages="$errors->get('empleado_id')" />
                    </div>
                </div>

                <hr class="border-gray-200 my-1 col-span-full">
                <div class="col-span-full">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-md bg-emerald-500/15 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Contraseña</span>
                        <span class="text-gray-400 text-xs">(dejar vacío para mantener)</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Nueva contraseña</label>
                    <input id="password" name="password" type="password">
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password">
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>

                <div class="flex items-center gap-3 pt-2 col-span-full">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cambiarRol(rol) {
    var seccionEmp = document.getElementById('seccionEmpleado');
    if (rol === 'admin' || rol === 'cotizador' || rol === 'operador') {
        seccionEmp.style.display = '';
    } else {
        seccionEmp.style.display = 'none';
    }
}
window.addEventListener('DOMContentLoaded', function() {
    var select = document.getElementById('role');
    if (select && select.value) {
        cambiarRol(select.value);
    }
});
</script>
@endpush