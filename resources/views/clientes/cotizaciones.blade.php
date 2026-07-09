@extends('layouts.app')
@section('title', 'Mis Cotizaciones')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Mis Cotizaciones</h2>
        <p class="page-description">Todas tus cotizaciones de servicio de grúa.</p>
    </div>

</div>

<div class="card mb-5">
    <div class="card-body">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="label">Buscar</label>
                <input type="text" name="q" placeholder="Folio, origen o destino..." value="{{ request('q') }}" class="input">
            </div>
            <div>
                <label class="label">Estatus</label>
                <select name="estatus" class="input">
                    <option value="">Todos</option>
                    <option value="pendiente" @selected(request('estatus') === 'pendiente')>Pendiente</option>
                    <option value="aprobado" @selected(request('estatus') === 'aprobado')>Aprobado</option>
                    <option value="rechazado" @selected(request('estatus') === 'rechazado')>Rechazado</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary">Filtrar</button>
                <a href="{{ route('clientes.cotizaciones') }}" class="btn-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Servicio</th>
                        <th>Tipo</th>
                        <th>Vehículo</th>
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cotizaciones as $cotizacion)
                        <tr>
                            <td>
                                <span class="font-mono font-semibold text-sm">#{{ $cotizacion->folio }}</span>
                            </td>
                            <td class="text-gray-600 text-sm">
                                {{ $cotizacion->origen_direccion }} → {{ $cotizacion->destino_direccion }}
                            </td>
                            <td>
                                <span class="badge badge-gray">{{ $cotizacion->tipoServicio->nombre ?? '-' }}</span>
                            </td>
                            <td class="text-sm text-gray-600">
                                {{ $cotizacion->marca ?? '—' }} {{ $cotizacion->modelo ?? '' }}
                            </td>
                            <td class="font-semibold text-sm">
                                ${{ number_format($cotizacion->costo_total, 2) }}
                            </td>
                            <td>
                                @php
                                    $badges = [
                                        'pendiente' => 'badge-yellow',
                                        'aprobado' => 'badge-green',
                                        'rechazado' => 'badge-red',
                                    ];
                                @endphp
                                <span class="badge {{ $badges[$cotizacion->estatus] ?? 'badge-gray' }}">
                                    {{ ucfirst($cotizacion->estatus) }}
                                </span>
                            </td>
                            <td class="text-gray-500 text-sm">{{ $cotizacion->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex items-center gap-1 justify-end">
                                    <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="btn-icon" title="Ver detalle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if ($cotizacion->estatus === 'pendiente')
                                        <form method="POST" action="{{ route('clientes.cotizaciones.aprobar', $cotizacion) }}" class="inline" data-confirm-title="✅ Aprobar cotización" data-confirm="Al aprobar esta cotización se generará un servicio de grúa automáticamente. ¿Deseas continuar?" data-confirm-icon="success" data-confirm-button="Sí, aprobar">
                                            @csrf
                                            <button type="submit" class="btn-icon text-emerald-600 hover:bg-emerald-50" title="Aprobar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                        <button type="button" class="btn-icon text-red-500 hover:bg-red-50" title="Rechazar" onclick="rechazarCotizacion('{{ $cotizacion->folio }}', {{ $cotizacion->id }})">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-8 text-gray-500">No hay cotizaciones registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $cotizaciones->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function rechazarCotizacion(folio, id) {
    Swal.fire({
        title: '❌ Rechazar cotización',
        html: `
            <p class="text-sm text-gray-600 mb-3">Cotización #${folio}</p>
            <textarea id="motivo-rechazo" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none transition-all" placeholder="Indica por qué rechazas esta cotización (opcional)..."></textarea>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        reverseButtons: true,
        customClass: {
            popup: 'swal-popup-custom',
            title: 'swal-title-custom',
            confirmButton: 'swal-confirm-custom',
            cancelButton: 'swal-cancel-custom',
        },
        didRender: function() {
            document.getElementById('motivo-rechazo').focus();
        },
        preConfirm: function() {
            return document.getElementById('motivo-rechazo').value;
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/panel/cotizaciones/' + id + '/rechazar';
            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            if (result.value) {
                var motivo = document.createElement('input');
                motivo.type = 'hidden';
                motivo.name = 'motivo';
                motivo.value = result.value;
                form.appendChild(motivo);
            }
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
