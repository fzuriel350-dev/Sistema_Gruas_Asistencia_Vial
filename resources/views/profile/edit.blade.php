<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi Perfil</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if ($user->empleado && $user->isOperador())
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Información Laboral</h2>
                            <p class="mt-1 text-sm text-gray-600">Datos de tu empleado y licencia de conducir.</p>
                        </header>

                        <div class="mt-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->nombreCompleto() }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Puesto</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->puesto ?: '—' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->telefono ?: '—' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Oficina</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->oficina?->nombre ?: '—' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->direccion ?: '—' }}</p>
                            </div>
                        </div>

                        @if ($user->empleado->operador)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-800 mb-4">Licencia de Conducir</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipo de licencia</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->operador->licencia_tipo ?: '—' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Vencimiento de licencia</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->operador->licencia_año_vencimiento?->format('d/m/Y') ?: '—' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Vencimiento federal</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->operador->licencia_vencimiento_federal?->format('d/m/Y') ?: '—' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Disponible</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->operador->disponible ? 'Sí' : 'No' }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-xs text-gray-400">Los datos de licencia solo pueden ser modificados por un administrador.</p>
                        </div>
                        @endif
                    </section>
                </div>
            </div>
            @endif

            @if ($user->empleado && $user->isCotizador())
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Información Laboral</h2>
                            <p class="mt-1 text-sm text-gray-600">Datos de tu empleado.</p>
                        </header>

                        <div class="mt-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->nombreCompleto() }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Puesto</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->puesto ?: '—' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->telefono ?: '—' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Oficina</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->oficina?->nombre ?: '—' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->direccion ?: '—' }}</p>
                            </div>
                            @if ($user->empleado->cotizador && $user->empleado->cotizador->zona_cobertura)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Zona de cobertura</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->empleado->cotizador->zona_cobertura }}</p>
                            </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
