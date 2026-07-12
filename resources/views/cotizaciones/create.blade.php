@extends('layouts.app')@section('title', 'Nueva Cotización')@section('content')<div class="max-w-7xl mx-auto">
<form method="POST" action="{{ route('cotizaciones.store') }}" x-data="cotizacionForm()">        @csrf
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
<select name="cliente_id" x-model="cliente_id" @@change="seleccionarCliente()">
<option value="">Sin cliente registrado...</option>                                    @foreach ($clientes as $cliente)                                    <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>                                        {{ $cliente->nombre }}                                    </option>                                    @endforeach                                </select>                                @error('cliente_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Aseguradora</label>
<select name="aseguradora_id" x-model="aseguradora_id" @@change="aseguradora_id = $event.target.value; filtrarConvenios(); resetearConvenio()" required>
<option value="">Seleccionar...</option>                                    @foreach ($aseguradoras as $a)                                    <option value="{{ $a->id }}" @selected(old('aseguradora_id') == $a->id)>{{ $a->nombre }}</option>                                    @endforeach                                </select>                                @error('aseguradora_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
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
<input type="text" name="origen_direccion" value="{{ old('origen_direccion') }}" placeholder="Av. Reforma 123, Col. Centro" required>                                @error('origen_direccion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Latitud origen</label>
<input type="text" name="origen_lat" value="{{ old('origen_lat') }}" placeholder="19.432608" step="any">
</div>
<div class="form-group">
<label>Longitud origen</label>
<input type="text" name="origen_lng" value="{{ old('origen_lng') }}" placeholder="-99.133209" step="any">
</div>
<div class="form-group">
<label>Destino</label>
<input type="text" name="destino_direccion" value="{{ old('destino_direccion') }}" placeholder="Periférico 456, Col. Granada" required>                                @error('destino_direccion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
<div class="form-group">
<label>Latitud destino</label>
<input type="text" name="destino_lat" value="{{ old('destino_lat') }}" placeholder="19.432608" step="any">
</div>
<div class="form-group">
<label>Longitud destino</label>
<input type="text" name="destino_lng" value="{{ old('destino_lng') }}" placeholder="-99.133209" step="any">
</div>
<div class="form-group">
<label>Tipo de servicio</label>
<select name="tipo_servicio_id" required>
<option value="">Seleccionar...</option>                                    @foreach ($tiposServicio as $ts)                                    <option value="{{ $ts->id }}" @selected(old('tipo_servicio_id') == $ts->id)>{{ $ts->nombre }}</option>                                    @endforeach                                </select>                                @error('tipo_servicio_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror                            </div>
</div>
<div class="map-placeholder mt-3 h-40">
<div class="map-grid">
</div>
<div class="map-content absolute inset-0 flex flex-col items-center justify-center">
<div class="text-3xl mb-2">📍</div>
<div class="font-semibold text-gray-600">Mapa de ruta</div>
<div class="text-xs text-gray-500" x-text="origen_direccion && destino_direccion ? `${origen_direccion} → ${destino_direccion} (${distancia_km} km)` : 'Ingresa origen y destino'">
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
<input type="number" name="tiempo_estimado_minutos" x-model="tiempo_estimado_minutos" placeholder="25" required>
</div>
</div>
<div class="mt-3 flex flex-col gap-3">
<label class="route-card cursor-pointer" :style="incluye_peajes == '0' ? 'border-color: var(--geg-yellow); background: linear-gradient(135deg, #FFFEF0, #FFF9C4); box-shadow: 0 0 0 3px rgba(255, 213, 0, 0.2);' : ''" x-on:click="incluye_peajes = '0'; costo_aprox_casetas = 0">
<div class="flex items-center gap-3">
<div class="w-5 h-5 rounded-full border-2 flex items-center justify-center" :style="incluye_peajes == '0' ? 'border-color: var(--geg-yellow); background: var(--geg-yellow);' : ''">
<div x-show="incluye_peajes == '0'" class="w-2 h-2 rounded-full bg-white"></div>
</div>
<div>
<div class="route-title">Ruta 1 — Sin peaje</div>
<div class="route-meta">
<span>📍 <span x-text="distancia_km || 0"></span> km</span>
<span>⏱ <span x-text="tiempo_estimado_minutos || 0"></span> min</span>
</div>
</div>
<div class="route-price ml-auto" x-show="sinPeajeTotal() > 0" x-text="'$' + formatPrice(sinPeajeTotal())"></div>
</div>
</label>
<label class="route-card cursor-pointer" :style="incluye_peajes == '1' ? 'border-color: var(--geg-yellow); background: linear-gradient(135deg, #FFFEF0, #FFF9C4); box-shadow: 0 0 0 3px rgba(255, 213, 0, 0.2);' : ''" x-on:click="incluye_peajes = '1'">
<div class="flex items-center gap-3">
<div class="w-5 h-5 rounded-full border-2 flex items-center justify-center" :style="incluye_peajes == '1' ? 'border-color: var(--geg-yellow); background: var(--geg-yellow);' : ''">
<div x-show="incluye_peajes == '1'" class="w-2 h-2 rounded-full bg-white"></div>
</div>
<div class="flex-1">
<div class="route-title">Ruta 2 — Con peaje</div>
<div class="route-meta">
<span>📍 <span x-text="distancia_km || 0"></span> km</span>
<span>⏱ <span x-text="tiempo_estimado_minutos || 0"></span> min</span>
</div>
<div class="mt-2 flex items-center gap-2">
<span class="text-xs text-gray-500">Costo aprox. casetas:</span>
<input type="number" class="w-24 px-2 py-0.5 text-xs border rounded" step="1" x-model.number="costo_aprox_casetas" x-on:click.stop>
</div>
</div>
<div class="route-price" x-show="conPeajeTotal() > 0" x-text="'$' + formatPrice(conPeajeTotal())"></div>
</div>
</label>
<input type="hidden" name="costo_aprox_casetas" :value="costo_aprox_casetas">
<input type="hidden" name="costo_banderazo" :value="costo_banderazo">
<input type="hidden" name="costo_km" :value="costo_km">
<input type="hidden" name="km_excedente" :value="Math.max(0, (distancia_km || 0) - (km_incluidos || 0))">
</div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3>Detalles y observaciones</h3></div>
                <div class="card-body">
                    <textarea name="notas" rows="3" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all" placeholder="Notas internas, instrucciones especiales...">{{ old('notas') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
<a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Cancelar</a>
<button type="submit" class="btn btn-primary">Generar cotización</button>
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
<div class="cost-row" x-show="costo_aprox_casetas > 0">
<span>Casetas</span>
<span x-text="'$' + formatPrice(costo_aprox_casetas)">
</span>
</div>
<div class="cost-row total">
<span>Total estimado</span>
<span x-text="'$' + formatPrice(total())">
</span>
</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Convenio aplicable</h3>
</div>
<div class="card-body">                        @if ($convenios->count())                        <div class="form-group">
<select name="convenio_aplicado_id" x-model="convenio_aplicado_id" @@change="actualizarConvenio()">
<option value="" data-aseguradora-id="" data-costo-banderazo="0" data-costo-km="0" data-km-incluidos="0" data-cubre-casetas="0">Sin convenio</option>                                @foreach ($convenios as $c)                                <option value="{{ $c->id }}"                                    data-aseguradora-id="{{ $c->aseguradora_id }}"                                    data-descuento="{{ $c->descuento }}"                                    data-costo-banderazo="{{ $c->costo_banderazo }}"                                    data-costo-km="{{ $c->costo_km }}"                                    data-km-incluidos="{{ $c->km_incluidos }}"                                    data-cubre-casetas="{{ $c->cubre_casetas_peaje ? 1 : 0 }}">                                    {{ $c->nombre }} ({{ $c->descuento }}% descuento)                                </option>                                @endforeach                            </select>
</div>
<template x-if="convenio_aplicado_id">
<div class="flex items-center gap-3 p-3 rounded-lg border" style="background: #f0fdf4; border-color: #bbf7d0;">
<div class="text-2xl">✅</div>
<div>
<div class="font-semibold text-sm" x-text="convenioNombre">
</div>
<div class="text-xs text-gray-500" x-text="'Descuento: ' + descuento_porcentaje + '%'"></div>
<div class="text-xs text-gray-500 mt-1" x-text="'Banderazo: $' + formatPrice(costo_banderazo) + ' | Km: $' + formatPrice(costo_km)">
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
<script>
function cotizacionForm() {
    return {
        init() {
            if (this.cliente_id) {
                this.$nextTick(() => { this.filtrarAseguradoras(); this.filtrarConvenios(); });
            }
        },
        clientesAseguradora: { @foreach ($clientes as $cliente) {{ $cliente->id }}: '{{ $cliente->aseguradora_id }}', @endforeach },
        cliente_id: '{{ old('cliente_id') }}',
        aseguradora_id: '{{ old('aseguradora_id') }}',
        destino_direccion: '{{ old('destino_direccion') }}',
        origen_direccion: '{{ old('origen_direccion') }}',
        distancia_km: {{ old('distancia_km', 0) }},
        tiempo_estimado_minutos: {{ old('tiempo_estimado_minutos', 0) }},
        incluye_peajes: '{{ old('incluye_peajes', '0') }}',
        costo_aprox_casetas: {{ old('costo_aprox_casetas', 0) }},
        costo_banderazo: 0,
        costo_km: 0,
        km_incluidos: 0,
        convenio_aplicado_id: '{{ old('convenio_aplicado_id') }}',
        descuento_porcentaje: 0,
        convenioNombre: '',
        formatPrice(v) { return v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') },
        costoKilometraje() { return Math.max(0, (this.distancia_km || 0) - (this.km_incluidos || 0)) * this.costo_km },
        baseSinPeaje() { return this.costo_banderazo + this.costoKilometraje() },
        baseConPeaje() { return this.costo_banderazo + this.costoKilometraje() + (this.costo_aprox_casetas || 0) },
        sinPeajeTotal() { const b = this.baseSinPeaje(); return this.descuento_porcentaje > 0 ? b * (1 - this.descuento_porcentaje / 100) : b },
        conPeajeTotal() { const b = this.baseConPeaje(); return this.descuento_porcentaje > 0 ? b * (1 - this.descuento_porcentaje / 100) : b },
        total() { return this.incluye_peajes == '1' ? this.conPeajeTotal() : this.sinPeajeTotal() },
        seleccionarCliente() {
            const id = this.cliente_id;
            this.aseguradora_id = id && this.clientesAseguradora[id] ? this.clientesAseguradora[id] : '';
            this.filtrarAseguradoras();
            this.filtrarConvenios();
            this.resetearConvenio();
        },
        filtrarAseguradoras() {
            const sel = document.querySelector('[name="aseguradora_id"]');
            for (const opt of sel.options) {
                if (!opt.value) { opt.hidden = !this.aseguradora_id; continue; }
                opt.hidden = opt.value !== this.aseguradora_id;
            }
        },
        filtrarConvenios() {
            const sel = document.querySelector('[name="convenio_aplicado_id"]');
            for (const opt of sel.options) {
                if (!opt.value) { opt.hidden = false; continue; }
                const asegId = opt.dataset.aseguradoraId || '';
                opt.hidden = asegId !== this.aseguradora_id;
            }
        },
        resetearConvenio() {
            this.convenio_aplicado_id = '';
            this.descuento_porcentaje = 0;
            this.costo_banderazo = 0;
            this.costo_km = 0;
            this.km_incluidos = 0;
            this.convenioNombre = '';
            this.incluye_peajes = '0';
            this.costo_aprox_casetas = 0;
        },
        actualizarConvenio() {
            const sel = document.querySelector('[name="convenio_aplicado_id"]');
            const opt = sel.options[sel.selectedIndex];
            if (opt && opt.value) {
                this.descuento_porcentaje = parseFloat(opt.dataset.descuento) || 0;
                this.costo_banderazo = parseFloat(opt.dataset.costoBanderazo) || 0;
                this.costo_km = parseFloat(opt.dataset.costoKm) || 0;
                this.km_incluidos = parseInt(opt.dataset.kmIncluidos) || 0;
                this.convenioNombre = opt.text;
                if (parseInt(opt.dataset.cubreCasetas)) {
                    this.incluye_peajes = '1';
                }
            } else {
                this.descuento_porcentaje = 0;
                this.costo_banderazo = 0;
                this.costo_km = 0;
                this.km_incluidos = 0;
                this.convenioNombre = '';
                this.incluye_peajes = '0';
                this.costo_aprox_casetas = 0;
            }
        }
    }
}
</script>
@endpush