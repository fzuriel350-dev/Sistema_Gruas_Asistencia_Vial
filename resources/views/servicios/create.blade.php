@extends('layouts.app')@section('title', 'Nuevo Servicio')@section('content')<div class="max-w-7xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Nuevo Servicio</h3>
<a href="{{ route('servicios.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('servicios.store') }}" x-data="{ tipo: @json(old('tipo_servicio_id') ?? null), mostrarDesc: @json((bool) old('descripcion')) }">                @csrf
<div class="form-grid">
<div class="form-group">
<label>Cotización</label>
<select name="cotizacion_id" required>
<option value="">Seleccionar cotización...</option>                        @foreach ($cotizaciones as $c)                            <option value="{{ $c->id }}" @selected(old('cotizacion_id') == $c->id)>{{ $c->folio }} — {{ $c->cliente?->nombre }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('cotizacion_id')" />
</div>
<div class="form-group">
<label>Operador</label>
<select name="operador_id" required>
<option value="">Seleccionar operador...</option>                        @foreach ($operadores as $op)                            <option value="{{ $op->id }}" @selected(old('operador_id') == $op->id)>{{ $op->empleado?->nombreCompleto() }} (Disponible)</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('operador_id')" />
</div>
<div class="form-group">
<label>Unidad</label>
<select name="unidad_id" required>
<option value="">Seleccionar unidad...</option>                        @foreach ($unidades as $u)                            <option value="{{ $u->id }}" @selected(old('unidad_id') == $u->id)>{{ $u->marca }} — {{ $u->placas }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('unidad_id')" />
</div>
<div class="form-group">
<label>Oficina</label>
<select name="oficina_id">
<option value="">Sin oficina</option>                        @foreach ($oficinas as $of)                            <option value="{{ $of->id }}" @selected(old('oficina_id') == $of->id)>{{ $of->nombre }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('oficina_id')" />
</div>
<div class="form-group full-width">
<label>Tipo de Servicio</label>
<input type="hidden" name="tipo_servicio_id" x-bind:value="tipo">
<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-1">                        @foreach ($tiposServicio as $ts)                            <button type="button" @@click="tipo = {{ $ts->id }}; mostrarDesc = false"                                class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 text-sm font-medium transition-all duration-150"                                x-bind:class="tipo === {{ $ts->id }} ? 'border-[#FFD500] bg-[#FFF8DC] text-[#1a1a2e] shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50'">
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
<textarea name="descripcion" rows="2" class="form-input" placeholder="Describe el problema del vehículo...">{{ old('descripcion') }}</textarea>
<x-input-error :messages="$errors->get('descripcion')" />
</div>
</div>
<div class="form-group">
<label>Fecha de Inicio</label>
<input name="fecha_inicio" type="datetime-local" value="{{ old('fecha_inicio') }}" required>
<x-input-error :messages="$errors->get('fecha_inicio')" />
</div>
<div class="form-group full-width">
<label>Observaciones</label>
<textarea name="observaciones" rows="2" placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
<x-input-error :messages="$errors->get('observaciones')" />
</div>
</div>
<div class="flex items-center gap-3 pt-4">
<button type="submit" class="btn btn-primary">Crear Servicio</button>
<a href="{{ route('servicios.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection