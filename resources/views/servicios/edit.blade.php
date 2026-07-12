@extends('layouts.app')@section('title', 'Editar Servicio #'.$servicio->id)@section('content')<div class="max-w-7xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Editar Servicio #{{ $servicio->id }}</h3>
<a href="{{ route('servicios.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('servicios.update', $servicio) }}" x-data="{
    tipo: @json(old('tipo_servicio_id') ?? $servicio->tipo_servicio_id),
    mostrarDesc: @json((bool) (old('descripcion') ?? $servicio->descripcion)),
    kmIncluidos: {{ $servicio->cotizacion?->convenio?->km_incluidos ?? 0 }},
    costoKm: {{ $servicio->cotizacion?->costo_km ?? 0 }},
    convenioNombre: @js($servicio->cotizacion?->convenio?->nombre ?? ''),
    kmsCobrados: {{ old('kms_cobrados_reales', $servicio->kms_cobrados_reales) ?? 0 }},
    cargosExtras: {{ old('cargos_extras', $servicio->cargos_extras) ?? 0 }},
    motivoCargos: @js(old('motivo_cargos_extras', $servicio->motivo_cargos_extras) ?? ''),
    calcularCargoExtra() {
        if (this.kmIncluidos <= 0 || this.kmsCobrados <= 0) {
            Swal.fire({ title: 'Sin límite', text: 'Este servicio no tiene convenio con km incluidos.', icon: 'info', confirmButtonColor: '#6b7280' });
            return;
        }
        const exceso = this.kmsCobrados - this.kmIncluidos;
        if (exceso <= 0) {
            this.cargosExtras = 0;
            this.motivoCargos = '';
            Swal.fire({ title: 'Dentro del límite', html: '<p class="text-sm text-gray-500">Km recorridos: <strong>' + this.kmsCobrados + '</strong><br>Km incluidos: <strong>' + this.kmIncluidos + '</strong><br><br>No excede el límite. No aplica cargo extra.</p>', icon: 'success', confirmButtonColor: '#16a34a' });
            return;
        }
        const cargo = exceso * this.costoKm;
        Swal.fire({
            title: 'Cargo extra por KM',
            html: '<div class="text-left text-sm space-y-2"><p>Km recorridos: <strong>' + this.kmsCobrados + '</strong></p><p>Km incluidos: <strong>' + this.kmIncluidos + '</strong></p><p>Km excedente: <strong>' + exceso + '</strong></p><p>Costo por km: <strong>$' + this.costoKm.toFixed(2) + '</strong></p><hr><p class="text-base font-bold">Cargo extra: <span style="color:#16a34a">$' + cargo.toFixed(2) + '</span></p></div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aplicar cargo',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#16a34a',
            input: 'text',
            inputLabel: 'Motivo del cargo extra',
            inputPlaceholder: 'Ej: Exceso de 5 km por ruta alternativa',
            inputValue: 'Exceso de ' + exceso + ' km sobre el límite de ' + this.kmIncluidos + ' km incluidos',
            preConfirm: function(motivo) {
                if (!motivo || !motivo.trim()) { Swal.showValidationMessage('Ingresa un motivo'); return false; }
                return motivo;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                this.cargosExtras = cargo;
                this.motivoCargos = result.value;
            }
        });
    }
}">                @csrf @method('PATCH')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
<div class="space-y-5">
<div class="card p-0 border-0 shadow-none">
<div class="card-header bg-transparent px-0 pt-0">
<h3>Asignación</h3>
</div>
<div class="card-body px-0 pb-0">
<div class="form-grid">
<div class="form-group">
<label>Operador</label>
<select name="operador_id" required>                        @foreach ($operadores as $op)                            <option value="{{ $op->id }}" @selected(old('operador_id', $servicio->operador_id) == $op->id)>{{ $op->empleado?->nombreCompleto() }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('operador_id')" />
</div>
<div class="form-group">
<label>Unidad</label>
<select name="unidad_id" required>                        @foreach ($unidades as $u)                            <option value="{{ $u->id }}" @selected(old('unidad_id', $servicio->unidad_id) == $u->id)>{{ $u->marca }} — {{ $u->placas }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('unidad_id')" />
</div>
<div class="form-group">
<label>Oficina</label>
<select name="oficina_id">                        @foreach ($oficinas as $of)                            <option value="{{ $of->id }}" @selected(old('oficina_id', $servicio->oficina_id) == $of->id)>{{ $of->nombre }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('oficina_id')" />
</div>
</div>
</div>
</div>
<div class="card p-0 border-0 shadow-none">
<div class="card-header bg-transparent px-0 pt-0">
<h3>Detalles del Servicio</h3>
</div>
<div class="card-body px-0 pb-0">
<div class="form-grid">
<div class="form-group full-width">
<label>Tipo de Servicio</label>
<input type="hidden" name="tipo_servicio_id" x-bind:value="tipo">
<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-1">                        @foreach ($tiposServicio as $ts)                            <button type="button" @@click="tipo = {{ $ts->id }}; mostrarDesc = false"                                class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 text-sm font-medium transition-all duration-150"                                x-bind:class="tipo === {{ $ts->id }} ? 'border-[var(--geg-yellow)] bg-[color:var(--geg-yellow-light)] text-[#1a1a2e] shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50'">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<span>{{ $ts->nombre }}</span>                                @if ($ts->descripcion)                                    <span class="text-[10px] text-gray-400 text-center leading-tight">{{ $ts->descripcion }}</span>                                @endif                            </button>                        @endforeach                    </div>
<x-input-error :messages="$errors->get('tipo_servicio_id')" />
<button type="button" @@click="mostrarDesc = !mostrarDesc" class="text-xs text-gray-500 hover:text-gray-700 mt-1.5 underline underline-offset-2">
<span x-show="!mostrarDesc">+ Especificar problema</span>
<span x-show="mostrarDesc">- Ocultar descripción</span>
</button>
<div x-show="mostrarDesc" class="mt-2">
<textarea name="descripcion" rows="2" class="form-input" placeholder="Describe el problema del vehículo...">{{ old('descripcion', $servicio->descripcion) }}</textarea>
<x-input-error :messages="$errors->get('descripcion')" />
</div>
</div>
<div class="form-group">
<label>Estado</label>
                    <select name="estado" required>
<option value="asignado" @selected(old('estado', $servicio->estado) === 'asignado')>Asignado</option>
<option value="inicio_servicio" @selected(old('estado', $servicio->estado) === 'inicio_servicio')>Inicio Servicio</option>
<option value="en_sitio_origen" @selected(old('estado', $servicio->estado) === 'en_sitio_origen')>En Sitio Origen</option>
<option value="en_carga" @selected(old('estado', $servicio->estado) === 'en_carga')>En Carga</option>
<option value="en_transito" @selected(old('estado', $servicio->estado) === 'en_transito')>En Tránsito</option>
<option value="en_sitio_destino" @selected(old('estado', $servicio->estado) === 'en_sitio_destino')>En Sitio Destino</option>
<option value="finalizado" @selected(old('estado', $servicio->estado) === 'finalizado')>Finalizado</option>
<option value="cancelado" @selected(old('estado', $servicio->estado) === 'cancelado')>Cancelado</option>
</select>
</div>
<div class="form-group">
<label>Inicio</label>
<input name="fecha_inicio" type="datetime-local" value="{{ old('fecha_inicio', $servicio->fecha_inicio?->format('Y-m-d\TH:i')) }}" required>
</div>
<div class="form-group">
<label>Fin</label>
<input name="fecha_fin" type="datetime-local" value="{{ old('fecha_fin', $servicio->fecha_fin?->format('Y-m-d\TH:i')) }}">
</div>
</div>
</div>
</div>
</div>
<div class="space-y-5">
<div class="card p-0 border-0 shadow-none">
<div class="card-header bg-transparent px-0 pt-0">
<h3>Kilometraje</h3>
</div>
<div class="card-body px-0 pb-0">
<div class="form-grid">
<div class="form-group">
<label>Kms salida</label>
<input type="number" name="kms_salida" value="{{ old('kms_salida', $servicio->kms_salida) }}" min="0">
</div>
<div class="form-group">
<label>Kms llegada cliente</label>
<input type="number" name="kms_llegada_cliente" value="{{ old('kms_llegada_cliente', $servicio->kms_llegada_cliente) }}" min="0">
</div>
<div class="form-group">
<label>Kms término servicio</label>
<input type="number" name="kms_termino_servicio" value="{{ old('kms_termino_servicio', $servicio->kms_termino_servicio) }}" min="0">
</div>
<div class="form-group">
<label>Kms regreso base</label>
<input type="number" name="kms_regreso_base" value="{{ old('kms_regreso_base', $servicio->kms_regreso_base) }}" min="0">
</div>
<div class="form-group">
<label>Kms cobrados reales</label>
<input type="number" name="kms_cobrados_reales" value="{{ old('kms_cobrados_reales', $servicio->kms_cobrados_reales) }}" min="0" x-model.number="kmsCobrados">
</div>
<div class="form-group">
<label>Costo final real ($)</label>
<input type="number" step="0.01" name="costo_final_real" value="{{ old('costo_final_real', $servicio->costo_final_real) }}" min="0">
</div>
</div>
@if ($servicio->cotizacion?->convenio && $servicio->cotizacion->convenio->km_incluidos > 0)
<div class="mt-4 p-4 rounded-xl border border-dashed" :class="cargosExtras > 0 ? 'border-emerald-300 bg-emerald-50' : 'border-gray-200 bg-gray-50'">
<div class="flex items-center justify-between">
<div>
<p class="text-sm font-semibold" :class="cargosExtras > 0 ? 'text-emerald-700' : 'text-gray-700'">Cargo extra por KM excedente</p>
<p class="text-xs text-gray-500 mt-0.5">Convenio: {{ $servicio->cotizacion->convenio->nombre }} — {{ $servicio->cotizacion->convenio->km_incluidos }} km incluidos</p>
</div>
<button type="button" @click="calcularCargoExtra()" class="btn btn-sm" :class="cargosExtras > 0 ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'btn-primary'">
<svg class="w-4 h-4 inline-block -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
 Calcular
</button>
</div>
<template x-if="cargosExtras > 0">
<div class="mt-3 flex items-center gap-3 p-3 rounded-lg bg-white border border-emerald-200">
<span class="text-lg font-bold text-emerald-700" x-text="'$' + Number(cargosExtras).toFixed(2)"></span>
<span class="text-xs text-gray-500" x-text="motivoCargos"></span>
</div>
</template>
</div>
@endif
<input type="hidden" name="cargos_extras" x-model.number="cargosExtras">
<input type="hidden" name="motivo_cargos_extras" x-model="motivoCargos">
</div>
</div>
</div>
<div class="card p-0 border-0 shadow-none">
<div class="card-header bg-transparent px-0 pt-0">
<h3>Observaciones</h3>
</div>
<div class="card-body px-0 pb-0">
<div class="form-group">
<textarea name="observaciones" rows="3" placeholder="Notas adicionales...">{{ old('observaciones', $servicio->observaciones) }}</textarea>
</div>
</div>
</div>
</div>
</div>
<div class="flex items-center gap-3 pt-4">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="{{ route('servicios.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection