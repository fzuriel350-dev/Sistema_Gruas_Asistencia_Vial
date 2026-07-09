@extends('layouts.app')
@section('title', 'Servicio #' . $servicio->cotizacion->folio)
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="page-header">
        <div>
            <h2 class="page-title">Servicio #{{ $servicio->cotizacion->folio }}</h2>
            <p class="page-description">Información detallada y seguimiento del servicio.</p>
        </div>
        <a href="{{ route('clientes.servicios') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Regresar
        </a>
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
                        <span class="text-[11px] mt-1.5 font-medium {{ $completado ? 'text-gray-800' : ($actual ? 'text-[#FFD500]' : 'text-gray-400') }}">
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
        <div class="card">
            <div class="card-header"><h3>Información del servicio</h3></div>
            <div class="card-body space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Folio cotización</span>
                    <span class="font-semibold text-sm">#{{ $servicio->cotizacion->folio }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Tipo de servicio</span>
                    <span class="badge badge-gray">{{ $servicio->tipoServicio->nombre ?? $servicio->cotizacion->tipoServicio->nombre ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Origen</span>
                    <span class="text-sm text-right max-w-[60%]">{{ $servicio->cotizacion->origen_direccion }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Destino</span>
                    <span class="text-sm text-right max-w-[60%]">{{ $servicio->cotizacion->destino_direccion }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Distancia</span>
                    <span class="text-sm">{{ number_format($servicio->cotizacion->distancia_km, 1) }} km</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Vehículo</span>
                    <span class="text-sm">{{ $servicio->cotizacion->marca ?? '—' }} {{ $servicio->cotizacion->modelo ?? '' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Fecha de creación</span>
                    <span class="text-sm">{{ $servicio->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>Asignación</h3></div>
            <div class="card-body space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Operador</span>
                    <span class="text-sm font-medium">{{ $servicio->operador?->empleado?->nombre ?? 'Sin asignar' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Teléfono operador</span>
                    <span class="text-sm">{{ $servicio->operador?->empleado?->telefono ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Unidad</span>
                    <span class="text-sm">{{ $servicio->unidad?->numero_economico ?? 'Sin asignar' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Placas unidad</span>
                    <span class="text-sm">{{ $servicio->unidad?->placas ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if ($servicio->descripcion)
        <div class="card">
            <div class="card-header"><h3>Notas del servicio</h3></div>
            <div class="card-body">
                <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $servicio->descripcion }}</p>
            </div>
        </div>
    @endif

    @if (!in_array($servicio->estado, ['finalizado', 'cancelado']))
        <div class="mt-6 text-right">
            <button onclick="cancelarServicio()" class="btn btn-secondary">
                Solicitar cancelación
            </button>
        </div>

        <form id="cancelar-form" method="POST" action="{{ route('clientes.servicios.cancelar', $servicio) }}" class="hidden">
            @csrf
            <input type="hidden" name="motivo" id="motivo-input">
        </form>

        @push('scripts')
        <script>
            function cancelarServicio() {
                Swal.fire({
                    title: 'Solicitar cancelación',
                    text: '¿Por qué deseas cancelar este servicio?',
                    input: 'textarea',
                    inputPlaceholder: 'Escribe el motivo...',
                    inputAttributes: { 'aria-label': 'Motivo de cancelación' },
                    showCancelButton: true,
                    confirmButtonText: 'Enviar solicitud',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc2626',
                    inputValidator: (value) => !value && 'Debes escribir un motivo',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('motivo-input').value = result.value;
                        document.getElementById('cancelar-form').submit();
                    }
                });
            }
        </script>
        @endpush
    @endif
</div>
@endsection
