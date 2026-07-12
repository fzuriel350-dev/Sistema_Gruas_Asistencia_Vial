@extends('layouts.app')
@section('title', 'Nuevo Empleado')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3>Nuevo Empleado</h3>
            <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-ghost">Volver</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('empleados.store') }}" class="form-grid">
                @csrf

                {{-- ═══════ ROL Y ACCESO ═══════ --}}
                <div class="col-span-full">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-md bg-[var(--geg-yellow)]/15 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[var(--geg-yellow)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Rol y Acceso</span>
                    </div>
                </div>

                <div class="form-group col-span-full">
                    <label for="role">Rol del usuario</label>
                    <select id="role" name="role" required onchange="cambiarRol(this.value)">
                        <option value="">Seleccionar rol...</option>
                        <option value="admin" @selected(old('role') === 'admin')>Administrador</option>
                        <option value="cotizador" @selected(old('role') === 'cotizador')>Cotizador</option>
                        <option value="operador" @selected(old('role') === 'operador')>Operador</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" />
                </div>

                {{-- ═══════ DATOS PERSONALES ═══════ --}}
                <hr class="border-gray-200 my-1 col-span-full">
                <div class="col-span-full">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-md bg-blue-500/15 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Datos Personales</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre(s)</label>
                    <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required>
                    <x-input-error :messages="$errors->get('nombre')" />
                </div>

                <div class="form-group">
                    <label for="apellido_paterno">Apellido Paterno</label>
                    <input id="apellido_paterno" name="apellido_paterno" type="text" value="{{ old('apellido_paterno') }}" required>
                    <x-input-error :messages="$errors->get('apellido_paterno')" />
                </div>

                <div class="form-group">
                    <label for="apellido_materno">Apellido Materno</label>
                    <input id="apellido_materno" name="apellido_materno" type="text" value="{{ old('apellido_materno') }}">
                    <x-input-error :messages="$errors->get('apellido_materno')" />
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" name="telefono" type="text" value="{{ old('telefono') }}">
                    <x-input-error :messages="$errors->get('telefono')" />
                </div>

                <div class="form-group col-span-full">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" rows="2">{{ old('direccion') }}</textarea>
                    <x-input-error :messages="$errors->get('direccion')" />
                </div>

                {{-- ═══════ DATOS DE ACCESO ═══════ --}}
                <hr class="border-gray-200 my-1 col-span-full">
                <div class="col-span-full">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-md bg-emerald-500/15 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Datos de Acceso</span>
                    </div>
                </div>

                <div class="form-group col-span-full">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input id="password" name="password" type="password" required>
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>

                {{-- ═══════ DATOS LABORALES (admin / cotizador / operador) ═══════ --}}
                <div id="seccionLaboral" style="display:none">
                    <hr class="border-gray-200 my-1 col-span-full">
                    <div class="col-span-full">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-md bg-purple-500/15 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Datos Laborales</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="oficina_id">Oficina</label>
                        <select id="oficina_id" name="oficina_id">
                            <option value="">Seleccionar...</option>
                            @foreach ($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" @selected(old('oficina_id') == $oficina->id)>{{ $oficina->nombre }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('oficina_id')" />
                    </div>

                    <div class="form-group">
                        <label for="puesto">Puesto</label>
                        <input id="puesto" name="puesto" type="text" value="{{ old('puesto') }}">
                        <x-input-error :messages="$errors->get('puesto')" />
                    </div>
                </div>

                {{-- ═══════ DATOS DEL OPERADOR (solo rol operador) ═══════ --}}
                <div id="seccionOperador" style="display:none">
                    <hr class="border-gray-200 my-1 col-span-full">
                    <div class="col-span-full">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-md bg-amber-500/15 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Datos del Operador</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="licencia_tipo">Tipo de licencia</label>
                        <select id="licencia_tipo" name="licencia_tipo">
                            <option value="">Seleccionar...</option>
                            <option value="A" @selected(old('licencia_tipo') === 'A')>A - Auto particular</option>
                            <option value="B" @selected(old('licencia_tipo') === 'B')>B - Autobús / Camión</option>
                            <option value="C" @selected(old('licencia_tipo') === 'C')>C - Transporte de carga</option>
                            <option value="D" @selected(old('licencia_tipo') === 'D')>D - Servicio público</option>
                        </select>
                        <x-input-error :messages="$errors->get('licencia_tipo')" />
                    </div>

                    <div class="form-group">
                        <label for="licencia_año_vencimiento">Vencimiento de licencia</label>
                        <input id="licencia_año_vencimiento" name="licencia_año_vencimiento" type="date" value="{{ old('licencia_año_vencimiento') }}">
                        <x-input-error :messages="$errors->get('licencia_año_vencimiento')" />
                    </div>

                    <div class="form-group">
                        <label for="licencia_vencimiento_federal">Vencimiento federal</label>
                        <input id="licencia_vencimiento_federal" name="licencia_vencimiento_federal" type="date" value="{{ old('licencia_vencimiento_federal') }}">
                        <x-input-error :messages="$errors->get('licencia_vencimiento_federal')" />
                    </div>
                </div>

                {{-- ═══════ DATOS DEL COTIZADOR (solo rol cotizador) ═══════ --}}
                <div id="seccionCotizador" style="display:none">
                    <hr class="border-gray-200 my-1 col-span-full">
                    <div class="col-span-full">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-md bg-cyan-500/15 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Datos del Cotizador</span>
                        </div>
                    </div>

                    <div class="form-group col-span-full">
                        <label for="zona_cobertura">Zona de cobertura</label>
                        <input id="zona_cobertura" name="zona_cobertura" type="text" value="{{ old('zona_cobertura') }}" placeholder="Ej: Zona Centro, Estado de México, Nacional...">
                        <x-input-error :messages="$errors->get('zona_cobertura')" />
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2 col-span-full">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('empleados.index') }}" class="btn btn-ghost">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cambiarRol(rol) {
    var lab = document.getElementById('seccionLaboral');
    var op = document.getElementById('seccionOperador');
    var cot = document.getElementById('seccionCotizador');
    var esEmpleado = (rol === 'admin' || rol === 'cotizador' || rol === 'operador');
    lab.style.display = esEmpleado ? '' : 'none';
    op.style.display = rol === 'operador' ? '' : 'none';
    cot.style.display = rol === 'cotizador' ? '' : 'none';
}
window.addEventListener('DOMContentLoaded', function() {
    var s = document.getElementById('role');
    if (s && s.value) cambiarRol(s.value);
});
</script>
@endpush