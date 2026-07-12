@extends('layouts.app')@section('title', 'Servicio #'.$servicio->id)@section('content')<div class="max-w-7xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div class="flex items-baseline gap-3">
<h2 class="text-2xl font-extrabold tracking-tight">Servicio #{{ $servicio->id }}</h2>
<span class="status @switch($servicio->estado) @case('asignado') status-pending @break @case('inicio_servicio') status-active @break @case('en_sitio_origen') status-active @break @case('en_carga') status-active @break @case('en_transito') status-active @break @case('en_sitio_destino') status-active @break @case('finalizado') status-success @break @case('cancelado') status-danger @break @endswitch">
<span class="status-dot"></span>
{{ str_replace('_', ' ', ucfirst($servicio->estado)) }}</span>
</div>
<div class="flex items-center gap-2">
<a href="{{ route('servicios.index') }}" class="btn btn-secondary">← Volver</a>
@if (!in_array($servicio->estado, ['finalizado', 'cancelado']))
    @if (auth()->user()->isAdmin() || auth()->user()->isCotizador())
    <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-primary">Editar</a>
    <button type="button" class="btn btn-danger" onclick="cancelarServicio({{ $servicio->id }})">Cancelar servicio</button>
    @endif
    @if (auth()->user()->isOperador())
    <button type="button" class="btn btn-primary" style="background:#2563eb;" onclick="avanzarEtapa({{ $servicio->id }}, '{{ $servicio->estado }}')">Avanzar etapa</button>
    @endif
    @if (auth()->user()->isOperador() && $servicio->operador_id === auth()->user()->empleado?->operador?->id)
    <button type="button" class="btn btn-ghost" style="background:#eab308;color:#000;" onclick="liberarServicio({{ $servicio->id }})">Liberar servicio</button>
    @endif
    @if ((auth()->user()->isAdmin() || auth()->user()->isCotizador()) && !$servicio->operador_id && !in_array($servicio->estado, ['finalizado', 'cancelado']))
    <button type="button" class="btn btn-primary" style="background:#16a34a;" onclick="asignarOperador({{ $servicio->id }})">Asignar operador</button>
    @endif
@endif
@if ($servicio->estado === 'finalizado' && !$servicio->factura && auth()->user()->isAdmin())
<form method="POST" action="{{ route('servicios.generar-factura', $servicio) }}" class="inline" onsubmit="return confirm('¿Generar factura para este servicio?')">
    @csrf
    <button type="submit" class="btn btn-primary" style="background:#16a34a;">Generar Factura</button>
</form>
@endif
@if ($servicio->factura && auth()->user()->isAdmin())
<a href="{{ route('facturas.show', $servicio->factura) }}" class="btn btn-primary" style="background:#7c3aed;">Ver Factura</a>
<a href="{{ route('facturas.descargar-pdf', $servicio->factura) }}" class="btn btn-primary" style="background:#2563eb;" target="_blank">Descargar PDF</a>
@endif
</div>
</div>
<div class="card mb-5">
<div class="card-header">
<h3>Progreso del servicio</h3>
@if ($servicio->estado === 'finalizado')
<span class="badge badge-green">Completado</span>
@elseif ($servicio->estado === 'cancelado')
<span class="badge badge-red">Cancelado</span>
@else
<span class="badge badge-purple">En curso</span>
@endif
</div>
<div class="card-body">
<div class="flex items-center gap-1">
@php
$pasos = [
['label' => 'Solicitado', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
['label' => 'Asignado', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
['label' => 'En camino', 'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
['label' => 'En proceso', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
['label' => 'Finalizado', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
];
@endphp
@foreach ($pasos as $i => $paso)
@php $completado = $progreso > $i; $actual = $progreso === $i + 1; @endphp
<div class="flex-1 flex flex-col items-center">
<div class="step-circle {{ $completado ? 'step-completed' : ($actual ? 'step-active' : 'step-inactive') }}">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $paso['icon'] }}" />
</svg>
</div>
<span class="text-[11px] mt-1.5 font-medium {{ $completado ? 'text-gray-800' : ($actual ? 'text-[var(--geg-yellow)]' : 'text-gray-400') }}">
{{ $paso['label'] }}
</span>
@if (!$loop->last)
<div class="step-line {{ $completado ? 'step-line-active' : '' }}"></div>
@endif
</div>
@endforeach
</div>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-5">
<div class="space-y-5">
<div class="card">
<div class="card-header">
<h3>Datos del Servicio</h3>
</div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Cliente</span>
<p class="font-semibold">{{ $servicio->cotizacion?->cliente?->nombre ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Cotización</span>
<p class="font-semibold">{{ $servicio->cotizacion?->folio ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Operador</span>
<p class="font-semibold">{{ $servicio->operador?->empleado?->nombreCompleto() ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Unidad</span>
<p class="font-semibold">{{ $servicio->unidad?->marca }} {{ $servicio->unidad?->placas ? '('.$servicio->unidad->placas.')' : '—' }}</p>
</div>
<div>
<span class="text-gray-500">Oficina</span>
<p class="font-semibold">{{ $servicio->oficina?->nombre ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Tipo de Servicio</span>
<p class="font-semibold">{{ $servicio->tipoServicio?->nombre ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Inicio</span>
<p class="font-semibold">{{ $servicio->fecha_inicio?->format('d/m/Y H:i') ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Fin</span>
<p class="font-semibold">{{ $servicio->fecha_fin?->format('d/m/Y H:i') ?: '—' }}</p>
</div>
@if ($servicio->descripcion)
<div class="col-span-2">
<span class="text-gray-500">Descripción</span>
<p class="font-semibold text-gray-700">{{ $servicio->descripcion }}</p>
</div>
@endif
</div>
</div>
</div>
@if ($servicio->observaciones)
<div class="card">
<div class="card-header">
<h3>Observaciones</h3>
</div>
<div class="card-body">
<p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $servicio->observaciones }}</p>
</div>
</div>
@endif
</div>
<div class="space-y-5">
<div class="card">
<div class="card-header">
<h3>Kilometraje</h3>
</div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Kms salida</span>
<p class="font-semibold">{{ $servicio->kms_salida ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Kms llegada cliente</span>
<p class="font-semibold">{{ $servicio->kms_llegada_cliente ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Kms término servicio</span>
<p class="font-semibold">{{ $servicio->kms_termino_servicio ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Kms regreso base</span>
<p class="font-semibold">{{ $servicio->kms_regreso_base ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Kms cobrados reales</span>
<p class="font-semibold">{{ $servicio->kms_cobrados_reales ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Costo final real</span>
<p class="font-semibold">${{ $servicio->costo_final_real ? number_format($servicio->costo_final_real, 2) : '—' }}</p>
</div>
@if ($servicio->cargos_extras > 0)
<div class="col-span-2 p-3 rounded-lg bg-amber-50 border border-amber-200">
<div class="flex items-center gap-2">
<span class="text-sm font-bold text-amber-700">Cargo extra KM: ${{ number_format($servicio->cargos_extras, 2) }}</span>
</div>
@if ($servicio->motivo_cargos_extras)
<p class="text-xs text-gray-500 mt-1">{{ $servicio->motivo_cargos_extras }}</p>
@endif
</div>
@endif
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script>
function liberarServicio(id) {
    Swal.fire({
        title: 'Liberar servicio',
        html: `
            <p class="text-sm text-gray-500 mb-3">Selecciona el motivo por el que liberas el servicio. Se notificará al cotizador para que asigne un nuevo operador.</p>
            <div class="space-y-3 text-left">
                <div>
                    <label class="text-xs font-semibold text-gray-600">Motivo</label>
                    <select id="tipo_incidencia" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none">
                        <option value="falla_mecanica">Falla mecánica</option>
                        <option value="llanta_ponchada">Llanta ponchada</option>
                        <option value="accidente">Accidente</option>
                        <option value="operador_siniestro">Operador siniestrado</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Descripción</label>
                    <textarea id="motivo_liberacion" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none" rows="3" placeholder="Describe lo ocurrido..." required></textarea>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, liberar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#eab308',
        reverseButtons: true,
        customClass: {
            popup: 'swal-popup-custom',
            title: 'swal-title-custom',
            confirmButton: 'swal-confirm-custom',
            cancelButton: 'swal-cancel-custom',
        },
        preConfirm: function() {
            const motivo = document.getElementById('motivo_liberacion')?.value;
            if (!motivo) {
                Swal.showValidationMessage('Describe el motivo de la liberación');
                return false;
            }
            return {
                tipo_incidencia: document.getElementById('tipo_incidencia')?.value,
                motivo_liberacion: motivo,
            };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/servicios/' + id + '/liberar';
            form.appendChild(hiddenInput('_token', '{{ csrf_token() }}'));
            form.appendChild(hiddenInput('tipo_incidencia', result.value.tipo_incidencia));
            form.appendChild(hiddenInput('motivo_liberacion', result.value.motivo_liberacion));
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function asignarOperador(id) {
    const operadores = @json($operadores->map(fn($o) => ['id' => $o->id, 'nombre' => $o->empleado?->nombreCompleto(), 'disponible' => $o->disponible]));
    const unidades = @json($unidades->map(fn($u) => ['id' => $u->id, 'name' => $u->marca . ' ' . $u->placas]));

    let opcionesOp = operadores.filter(o => o.disponible).map(o => `<option value="${o.id}">${o.nombre}</option>`).join('');
    let opcionesUn = '<option value="">— Sin unidad —</option>' + unidades.map(u => `<option value="${u.id}">${u.name}</option>`).join('');

    Swal.fire({
        title: 'Asignar operador',
        html: `
            <p class="text-sm text-gray-500 mb-3">Selecciona el operador que se hará cargo del servicio.</p>
            <div class="space-y-3 text-left">
                <div>
                    <label class="text-xs font-semibold text-gray-600">Operador</label>
                    <select id="operador_select" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none">
                        ${opcionesOp || '<option value="">No hay operadores disponibles</option>'}
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Unidad</label>
                    <select id="unidad_select" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none">
                        ${opcionesUn}
                    </select>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Asignar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#16a34a',
        reverseButtons: true,
        customClass: {
            popup: 'swal-popup-custom',
            title: 'swal-title-custom',
            confirmButton: 'swal-confirm-custom',
            cancelButton: 'swal-cancel-custom',
        },
        preConfirm: function() {
            const operadorId = document.getElementById('operador_select')?.value;
            if (!operadorId) {
                Swal.showValidationMessage('Selecciona un operador');
                return false;
            }
            return {
                operador_id: operadorId,
                unidad_id: document.getElementById('unidad_select')?.value || '',
            };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/servicios/' + id + '/asignar-operador';
            form.appendChild(hiddenInput('_token', '{{ csrf_token() }}'));
            form.appendChild(hiddenInput('operador_id', result.value.operador_id));
            if (result.value.unidad_id) {
                form.appendChild(hiddenInput('unidad_id', result.value.unidad_id));
            }
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function cancelarServicio(id) {
    Swal.fire({
        title: 'Cancelar servicio',
        html: `
            <p class="text-sm text-gray-500 mb-3">¿Estás seguro de cancelar este servicio? Se liberará al operador asignado.</p>
            <div class="space-y-3 text-left">
                <div>
                    <label class="text-xs font-semibold text-gray-600">Motivo de cancelación</label>
                    <select id="tipo_incidencia_cancel" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none">
                        <option value="cliente_cancela">Cliente cancela</option>
                        <option value="duplicado">Servicio duplicado</option>
                        <option value="falla_mecanica">Falla mecánica</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Descripción</label>
                    <textarea id="motivo_cancelacion" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none" rows="3" placeholder="Indica el motivo..." required></textarea>
                </div>
            </div>
        `,
        icon: 'error',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Volver',
        confirmButtonColor: '#dc2626',
        reverseButtons: true,
        customClass: {
            popup: 'swal-popup-custom',
            title: 'swal-title-custom',
            confirmButton: 'swal-confirm-custom',
            cancelButton: 'swal-cancel-custom',
        },
        preConfirm: function() {
            const motivo = document.getElementById('motivo_cancelacion')?.value;
            if (!motivo) {
                Swal.showValidationMessage('Describe el motivo de cancelación');
                return false;
            }
            return {
                tipo_incidencia: document.getElementById('tipo_incidencia_cancel')?.value,
                motivo_cancelacion: motivo,
            };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/servicios/' + id + '/cancelar';
            form.appendChild(hiddenInput('_token', '{{ csrf_token() }}'));
            form.appendChild(hiddenInput('tipo_incidencia', result.value.tipo_incidencia));
            form.appendChild(hiddenInput('motivo_cancelacion', result.value.motivo_cancelacion));
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function avanzarEtapa(id, estadoActual) {
    const campos = {
        'asignado': { title: '🚀 Iniciar servicio', label: 'Kilometraje de salida', name: 'kms_salida', required: true, type: 'number', placeholder: 'Ej: 12500' },
        'inicio_servicio': { title: '📍 Llegar al origen', label: 'Kilometraje al llegar al cliente', name: 'kms_llegada_cliente', required: true, type: 'number', placeholder: 'Ej: 12550' },
        'en_sitio_origen': { title: '⬆️ Vehículo en carga', label: false },
        'en_carga': { title: '🚛 En tránsito', label: 'Kilometraje al terminar servicio', name: 'kms_termino_servicio', required: true, type: 'number', placeholder: 'Ej: 12600' },
        'en_transito': { title: '📍 Llegar al destino', label: false },
        'en_sitio_destino': { title: '✅ Finalizar servicio', label: false },
    };

    const campo = campos[estadoActual];
    if (!campo) return;

    let html = '';
    if (campo.title.includes('Finalizar')) {
        html = `
            <p class="text-sm text-gray-500 mb-2">Registra los kilometrajes finales. El costo se calcula automáticamente.</p>
            <div class="space-y-3 text-left">
                <div><label class="text-xs font-semibold text-gray-600">Km regreso a base</label><input id="kms_regreso_base" type="number" min="0" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none" placeholder="Ej: 12700" required></div>
                <div><label class="text-xs font-semibold text-gray-600">Km a cobrar</label><input id="kms_cobrados_reales" type="number" min="0" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none" placeholder="Ej: 200" required></div>
            </div>
        `;
    } else if (campo.label) {
        html = `
            <div class="text-left">
                <label class="text-xs font-semibold text-gray-600">${campo.label}</label>
                <input id="${campo.name}" type="${campo.type}" min="0" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-200 outline-none" placeholder="${campo.placeholder}" required>
            </div>
        `;
    }

    Swal.fire({
        title: campo.title,
        html: html || '<p class="text-gray-500 text-sm">¿Avanzar al siguiente estado?</p>',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Sí, avanzar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2563eb',
        reverseButtons: true,
        customClass: {
            popup: 'swal-popup-custom',
            title: 'swal-title-custom',
            confirmButton: 'swal-confirm-custom',
            cancelButton: 'swal-cancel-custom',
        },
        preConfirm: function() {
            const data = {};
            if (campo.label && campo.name) {
                const val = document.getElementById(campo.name)?.value;
                if (campo.required && !val) {
                    Swal.showValidationMessage('Este campo es obligatorio');
                    return false;
                }
                data[campo.name] = val;
            }
            if (campo.title.includes('Finalizar')) {
                const camposFin = ['kms_regreso_base', 'kms_cobrados_reales'];
                for (const c of camposFin) {
                    const val = document.getElementById(c)?.value;
                    if (!val) {
                        Swal.showValidationMessage('Todos los campos son obligatorios');
                        return false;
                    }
                    data[c] = val;
                }
            }
            return data;
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var orden = ['asignado', 'inicio_servicio', 'en_sitio_origen', 'en_carga', 'en_transito', 'en_sitio_destino', 'finalizado'];
            var idx = orden.indexOf(estadoActual);
            var nuevoEstado = orden[idx + 1];
            if (!nuevoEstado) return;

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/servicios/' + id + '/avanzar';
            form.appendChild(hiddenInput('_token', '{{ csrf_token() }}'));
            form.appendChild(hiddenInput('_method', 'PATCH'));
            form.appendChild(hiddenInput('estado', nuevoEstado));
            if (result.value) {
                for (var k in result.value) {
                    form.appendChild(hiddenInput(k, result.value[k]));
                }
            }
            document.body.appendChild(form);
            form.submit();
        }
    });

}
function hiddenInput(name, value) {
    var inp = document.createElement('input');
    inp.type = 'hidden';
    inp.name = name;
    inp.value = value;
    return inp;
}
</script>

@endsection