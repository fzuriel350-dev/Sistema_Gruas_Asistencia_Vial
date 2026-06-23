@extends('layouts.app')@section('title', 'Notificaciones')@section('content')<div class="max-w-4xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Notificaciones</h3>
<form method="POST" action="{{ route('notificaciones.leer-todas') }}">                @csrf                <button type="submit" class="btn btn-sm btn-secondary">Marcar todas como leídas</button>
</form>
</div>
<div class="divide-y divide-gray-100">            @forelse ($notificaciones as $n)            <div class="flex items-start gap-3 px-5 py-4 {{ $n->estado === 'no_leida' ? 'bg-yellow-50/50' : '' }}">
<div class="w-2 h-2 rounded-full mt-1.5 {{ $n->estado === 'no_leida' ? 'bg-[#FFD500]' : 'bg-gray-300' }}">
</div>
<div class="flex-1 min-w-0">
<p class="text-sm text-gray-800">{{ $n->mensaje }}</p>
<div class="flex items-center gap-3 mt-1">
<span class="text-xs text-gray-500">{{ $n->created_at->diffForHumans() }}</span>                        @if ($n->estado === 'no_leida')                        <form method="POST" action="{{ route('notificaciones.leer', $n) }}">                            @csrf @method('PATCH')                            <button type="submit" class="text-xs text-[#FFD500] hover:underline font-medium">Marcar leída</button>
</form>                        @endif                    </div>
</div>
</div>            @empty            <div class="text-center py-10 text-gray-500">
<svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
</svg>
<p class="text-sm">No tienes notificaciones.</p>
</div>            @endforelse        </div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $notificaciones->links() }}
</div>
</div>
</div>@endsection
