@extends('layouts.app')
@section('title', 'Mi Perfil')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="page-header">
        <div>
            <h2 class="page-title">Mi Perfil</h2>
            <p class="page-description">Actualiza tu información personal.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-red-50 text-red-800 border border-red-200">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>Información de la cuenta</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('clientes.perfil.update') }}">
                @csrf
                <div class="grid grid-cols-1 gap-4 mb-5">
                    <div>
                        <label class="label">Nombre completo</label>
                        <input type="text" value="{{ Auth::user()->name }}" class="input bg-gray-50 text-gray-500" disabled readonly>
                        <p class="mt-1 text-xs text-gray-400">El nombre solo puede ser modificado por un administrador.</p>
                    </div>
                    <div>
                        <label class="label">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="input" required>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Cambiar contraseña</h4>
                    <p class="text-xs text-gray-500 mb-4">Deja estos campos en blanco si no deseas cambiar tu contraseña.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Nueva contraseña</label>
                            <input type="password" name="password" class="input" minlength="8">
                        </div>
                        <div>
                            <label class="label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" class="input">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection