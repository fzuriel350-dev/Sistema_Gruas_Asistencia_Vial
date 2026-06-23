@extends('layouts.app')@section('title', 'Nueva Cotización')@section('content')<div class="max-w-7xl mx-auto">
<form method="POST" action="{{ route('cotizaciones.store') }}" x-data="cotizacionForm()">        @csrf        <input type="hidden" name="action" x-bind:value="action">
<div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-5">
<div class="space-y-5">
<div class="card">
<div class="card-header">
<h3>Datos del Cliente</h3>
</div>
<div class="card-body">
<div class="form-grid">
<div class="form-group">
<label>Cliente</label>
<select name="cliente_id" required>
<option value="">Seleccionar cliente...</option>                                    @foreach ($clientes as $cliente)                                    <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>                                        {{ $cliente->nombre }}                                    </option>                                    @endforeach                                </select>                                @error('cliente_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Aseguradora</label>
<select name="aseguradora_id" required>
<option value="">Seleccionar...</option>                                    @foreach ($aseguradoras as $a)                                    <option value="{{ $a->id }}" @selected(old('aseguradora_id') == $a->id)>{{ $a->nombre }}</option>                                    @endforeach                                </select>                                @error('aseguradora_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>No. Póliza</label>
<input type="text" name="no_poliza" value="{{ old('no_poliza') }}" placeholder="POL-2026-XXXX">
</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Datos del Vehículo</h3>
</div>
<div class="card-body">
<div class="form-grid">
<div class="form-group">
<label>Marca</label>
<input type="text" name="marca" value="{{ old('marca') }}" placeholder="Nissan" required>                                @error('marca') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Modelo</label>
<input type="text" name="modelo" value="{{ old('modelo') }}" placeholder="Versa 2023" required>                                @error('modelo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Color</label>
<input type="text" name="color" value="{{ old('color') }}" placeholder="Gris">
</div>
<div class="form-group">
<label>Placas</label>
<input type="text" name="placas" value="{{ old('placas') }}" placeholder="ABC-1234" required>                                @error('placas') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Ubicación y Ruta</h3>
</div>
<div class="card-body">
<div class="form-grid">
<div class="form-group">
<label>Origen</label>
<input type="text" name="origen" value="{{ old('origen') }}" placeholder="Av. Reforma 123, Col. Centro" required>                                @error('origen') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Destino</label>
<input type="text" name="destino" value="{{ old('destino') }}" placeholder="Periférico 456, Col. Granada" required>                                @error('destino') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Tipo de servicio</label>
<select name="tipo_servicio_id" required>
<option value="">Seleccionar...</option>                                    @foreach ($tiposServicio as $ts)                                    <option value="{{ $ts->id }}" @selected(old('tipo_servicio_id') == $ts->id)>{{ $ts->nombre }}</option>                                    @endforeach                                </select>                                @error('tipo_servicio_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Tipo de ruta</label>
<select name="tipo_ruta" required>
<option value="local" @selected(old('tipo_ruta') === 'local')>Local</option>
<option value="foraneo" @selected(old('tipo_ruta') === 'foraneo')>Foráneo</option>
</select>                                @error('tipo_ruta') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
</div>
<div class="map-placeholder mt-3 h-40">
<div class="map-grid">
</div>
<div class="map-content absolute inset-0 flex flex-col items-center justify-center">
<div class="text-3xl mb-2">📍</div>
<div class="font-semibold text-gray-600">Mapa de ruta</div>
<div class="text-xs text-gray-500" x-text="origen && destino ? `${origen} → ${destino} (${distancia_km} km)` : 'Ingresa origen y destino'">
</div>
</div>
</div>
<div class="form-grid mt-4">
<div class="form-group">
<label>Distancia (km)</label>
<input type="number" step="0.1" name="distancia_km" x-model="distancia_km" placeholder="12.5" required>
</div>
<div class="form-group">
<label>Tiempo estimado (min)</label>
<input type="number" name="tiempo_estimado" x-model="tiempo_estimado" placeholder="25" required>
</div>
</div>
<div class="mt-3 flex flex-col gap-3">
<div class="route-card" :class="{ 'selected': !con_peaje }" @@click="con_peaje = false; num_casetas = 0; costo_casetas = 0">
<div>
<div class="route-title">Ruta 1 — Sin peaje</div>
<div class="route-meta">
<span>📍 <span x-text="distancia_km || 0">
</span> km</span>
<span>⏱ <span x-text="tiempo_estimado || 0">
</span> min</span>
</div>
</div>
<div class="route-price" x-text="'$' + formatPrice(sinPeajeTotal())">
</div>
</div>
<div class="route-card" :class="{ 'selected': con_peaje }" @@click="con_peaje = true">
<div>
<div class="route-title">Ruta 2 — Con peaje</div>
<div class="route-meta">
<span>📍 <span x-text="distancia_km || 0">
</span> km</span>
<span>⏱ <span x-text="tiempo_estimado || 0">
</span> min</span>
<span>💰 <input type="number" class="w-16 inline-block px-2 py-0.5 text-xs border rounded" step="1" x-model.number="num_casetas" @@click.stop> caseta(s)</span>
</div>
<div class="mt-2 flex items-center gap-2">
<span class="text-xs text-gray-500">Costo casetas:</span>
<input type="number" class="w-24 px-2 py-0.5 text-xs border rounded" step="1" x-model.number="costo_casetas" @@click.stop>
</div>
</div>
<div class="route-price" x-text="'$' + formatPrice(conPeajeTotal())">
</div>
</div>
<input type="hidden" name="con_peaje" x-bind:value="con_peaje">
<input type="hidden" name="num_casetas" x-bind:value="num_casetas">
<input type="hidden" name="costo_casetas" x-bind:value="costo_casetas">
</div>
<div class="form-grid mt-4">
<div class="form-group full-width">
<label>Cargos extras ($)</label>
<input type="number" step="0.01" name="extras" x-model.number="extras" placeholder="0.00">
</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Notas</h3>
</div>
<div class="card-body">
<div class="form-group">
<textarea name="notas" rows="3" placeholder="Notas adicionales...">{{ old('notas') }}</textarea>
</div>
</div>
</div>
<div class="form-actions">
<a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Cancelar</a>
<button type="button" @@click="action = 'draft'; $el.closest('form').submit()" class="btn btn-secondary">Guardar borrador</button>
<button type="button" @@click="action = 'generate'; $el.closest('form').submit()" class="btn btn-primary">Generar cotización</button>
</div>
</div>
<div class="space-y-5">
<div class="card">
<div class="card-header">
<h3>Resumen de costos</h3>
</div>
<div class="card-body">
<div class="cost-summary">
<div class="cost-row">
<span>Banderazo</span>
<span x-text="'$' + formatPrice(costo_banderazo)">
</span>
</div>
<div class="cost-row">
<span>Kilometraje (<span x-text="distancia_km || 0">
</span> km × <span x-text="costo_km">
</span>/km)</span>
<span x-text="'$' + formatPrice(costoKilometraje())">
</span>
</div>
<div class="cost-row">
<span>Casetas</span>
<span x-text="'$' + formatPrice(costo_casetas)">
</span>
</div>
<div class="cost-row">
<span>Extras</span>
<span x-text="'$' + formatPrice(extras)">
</span>
</div>
<template x-if="descuento_porcentaje > 0">
<div class="cost-row" style="color: var(--geg-success);">
<span>Descuento (<span x-text="descuento_porcentaje">
</span>%)</span>
<span x-text="'-$' + formatPrice(descuentoMonto())">
</span>
</div>
</template>
<div class="cost-row">
<span>IVA (16%)</span>
<span x-text="'$' + formatPrice(iva())">
</span>
</div>
<div class="cost-row total">
<span>Total estimado</span>
<span x-text="'$' + formatPrice(total())">
</span>
</div>
</div>
<div class="mt-4 text-xs text-gray-500">
<strong>Cobertura de seguro:</strong>
<div class="flex gap-2 mt-1">
<span class="status status-success">
<span class="status-dot">
</span> Cubre todo</span>
<span class="status status-pending">
<span class="status-dot">
</span> Parcial</span>
<span class="status status-danger">
<span class="status-dot">
</span> Sin cobertura</span>
</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Convenio aplicable</h3>
</div>
<div class="card-body">                        @if ($convenios->count())                        <div class="form-group">
<select name="convenio_id" x-model="convenio_id" @@change="actualizarConvenio()">
<option value="">Sin convenio</option>                                @foreach ($convenios as $c)                                <option value="{{ $c->id }}"                                    data-descuento="{{ $c->descuento }}"                                    data-cobertura="{{ $c->cobertura }}">                                    {{ $c->nombre }} ({{ $c->descuento }}% descuento)                                </option>                                @endforeach                            </select>
</div>
<template x-if="convenio_id">
<div class="flex items-center gap-3 p-3 rounded-lg border" style="background: #f0fdf4; border-color: #bbf7d0;">
<div class="text-2xl">✅</div>
<div>
<div class="font-semibold text-sm" x-text="convenioNombre">
</div>
<div class="text-xs text-gray-500" x-text="'Descuento: ' + descuento_porcentaje + '%'">
</div>
</div>
</div>
</template>                        @else                        <p class="text-sm text-gray-500">No hay convenios activos.</p>                        @endif                    </div>
</div>
</div>
</div>
</form>
</div>
@endsection

@push('scripts')
<script>function cotizacionForm() {    return {        action: 'draft',        destino: '{{ old('destino') }}',        origen: '{{ old('origen') }}',        distancia_km: {{ old('distancia_km', 0) }},        tiempo_estimado: {{ old('tiempo_estimado', 0) }},        con_peaje: {{ old('con_peaje', false) ? 'true' : 'false' }},        num_casetas: {{ old('num_casetas', 0) }},        costo_casetas: {{ old('costo_casetas', 0) }},        extras: {{ old('extras', 0) }},        costo_banderazo: 500,        costo_km: 120,        convenio_id: '{{ old('convenio_id') }}',        descuento_porcentaje: 0,        cobertura: 'sin_cobertura',        convenioNombre: '',        formatPrice(v) { return v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') },        costoKilometraje() { return (this.distancia_km || 0) * this.costo_km },        sinPeajeTotal() { return this.costo_banderazo + this.costoKilometraje() },        conPeajeTotal() { return this.costo_banderazo + this.costoKilometraje() + (this.costo_casetas || 0) },        subtotal() { return this.costo_banderazo + this.costoKilometraje() + (this.costo_casetas || 0) + (this.extras || 0) },        descuentoMonto() { return this.subtotal() * (this.descuento_porcentaje / 100) },        baseIva() { return this.subtotal() - this.descuentoMonto() },        iva() { return this.baseIva() * 0.16 },        total() { return this.baseIva() + this.iva() },        actualizarConvenio() {            const sel = document.querySelector('[name="convenio_id"]');            const opt = sel.options[sel.selectedIndex];            if (opt && opt.value) {                this.descuento_porcentaje = parseFloat(opt.dataset.descuento) || 0;                this.cobertura = opt.dataset.cobertura || 'sin_cobertura';                this.convenioNombre = opt.text;            } else {                this.descuento_porcentaje = 0;                this.cobertura = 'sin_cobertura';                this.convenioNombre = '';            }        }    }
</script>
@endpush
