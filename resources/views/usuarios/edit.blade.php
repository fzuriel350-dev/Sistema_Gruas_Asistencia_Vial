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

                <div class="form-group">
                    <label for="role">Rol</label>
                    <select id="role" name="role" required onchange="toggleRoleFields(this.value)">
                        <option value="admin" @selected(old('role', $usuario->role) === 'admin')>Admin</option>
                        <option value="cotizador" @selected(old('role', $usuario->role) === 'cotizador')>Cotizador</option>
                        <option value="operador" @selected(old('role', $usuario->role) === 'operador')>Operador</option>
                        <option value="cliente" @selected(old('role', $usuario->role) === 'cliente')>Cliente</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" />
                </div>

                <div class="form-group" id="empleadoField" @if($usuario->role === 'cliente') style="display:none" @endif>
                    <label for="empleado_id">Empleado vinculado</label>
                    <select id="empleado_id" name="empleado_id" onchange="toggleNuevoEmpleado(this.value)">
                        <option value="">Seleccionar...</option>
                        @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}" @selected(old('empleado_id', $usuario->empleado_id) == $empleado->id)>
                            {{ $empleado->nombreCompleto() }} {{ $empleado->puesto ? '(' . $empleado->puesto . ')' : '' }}
                        </option>
                        @endforeach
                        <option value="__nuevo__">+ Crear nuevo empleado</option>
                    </select>
                    <x-input-error :messages="$errors->get('empleado_id')" />
                </div>

                <div id="nuevoEmpleadoFields" style="display:none" class="form-grid col-span-full">
                    <hr class="border-gray-200 my-2 col-span-full">
                    <p class="text-sm text-gray-500 font-medium col-span-full">Nuevo empleado</p>

                    <div class="form-group">
                        <label for="emp_nombre">Nombre(s)</label>
                        <input id="emp_nombre" name="emp_nombre" type="text" value="{{ old('emp_nombre') }}">
                        <x-input-error :messages="$errors->get('emp_nombre')" />
                    </div>

                    <div class="form-group">
                        <label for="emp_apellido_paterno">Apellido Paterno</label>
                        <input id="emp_apellido_paterno" name="emp_apellido_paterno" type="text" value="{{ old('emp_apellido_paterno') }}">
                        <x-input-error :messages="$errors->get('emp_apellido_paterno')" />
                    </div>

                    <div class="form-group">
                        <label for="emp_apellido_materno">Apellido Materno</label>
                        <input id="emp_apellido_materno" name="emp_apellido_materno" type="text" value="{{ old('emp_apellido_materno') }}">
                        <x-input-error :messages="$errors->get('emp_apellido_materno')" />
                    </div>

                    <div class="form-group">
                        <label for="emp_telefono">Teléfono</label>
                        <input id="emp_telefono" name="emp_telefono" type="text" value="{{ old('emp_telefono') }}">
                        <x-input-error :messages="$errors->get('emp_telefono')" />
                    </div>

                    <div class="form-group">
                        <label for="emp_puesto">Puesto</label>
                        <input id="emp_puesto" name="emp_puesto" type="text" value="{{ old('emp_puesto') }}" placeholder="Operador">
                        <x-input-error :messages="$errors->get('emp_puesto')" />
                    </div>

                    <div class="form-group">
                        <label for="emp_direccion">Dirección</label>
                        <textarea id="emp_direccion" name="emp_direccion" rows="2">{{ old('emp_direccion') }}</textarea>
                        <x-input-error :messages="$errors->get('emp_direccion')" />
                    </div>
                </div>

                <hr class="border-gray-200 my-2">
                <p class="text-sm text-gray-500 font-medium">Cambiar contraseña <span class="text-gray-400 text-xs">(dejar vacío para mantener)</span></p>

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

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleRoleFields(role) {
    const field = document.getElementById('empleadoField');
    const nuevo = document.getElementById('nuevoEmpleadoFields');
    if (role === 'cliente') {
        field.style.display = 'none';
        nuevo.style.display = 'none';
    } else {
        field.style.display = '';
        toggleNuevoEmpleado(document.getElementById('empleado_id').value);
    }
}

function toggleNuevoEmpleado(val) {
    document.getElementById('nuevoEmpleadoFields').style.display = (val === '__nuevo__') ? '' : 'none';
}
</script>
@endpush
@endsection
