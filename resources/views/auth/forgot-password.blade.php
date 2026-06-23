<x-guest-layout>
<div class="mb-4 text-sm text-gray-600">        ¿Olvidaste tu contraseña? No hay problema. Indícanos tu correo y te enviaremos un enlace para restablecerla.    </div>
<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('password.email') }}">        @csrf        <div class="form-group">
<label for="email">Correo electrónico</label>
<input id="email" type="email" name="email" :value="old('email')" placeholder="tu@correo.com" required autofocus>
<x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
<button type="submit" class="btn btn-primary btn-block w-full py-3 mt-2">            Enviar enlace de recuperación        </button>
</form>
<div class="text-center mt-6 text-[13px] text-gray-500">
<a href="{{ route('login') }}" class="font-semibold" style="color: #E6A000;">Volver al inicio de sesión</a>
</div>
</x-guest-layout>
