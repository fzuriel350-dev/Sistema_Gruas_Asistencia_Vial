<x-guest-layout>
<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('login') }}">        @csrf        <div class="form-group">
<label for="email">Correo electrónico</label>
<input id="email" type="email" name="email" :value="old('email')" placeholder="tu@correo.com" required autofocus autocomplete="username">
<x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
<div class="form-group">
<label for="password">Contraseña</label>
<input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
<x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
<div class="flex items-center justify-between mb-5">
<label class="flex items-center gap-1.5 text-sm font-normal cursor-pointer">
<input type="checkbox" name="remember" class="rounded border-gray-300 focus:ring-offset-0" style="width: auto; color: {{ $empresa->color ?? '#E6A000' }}; --tw-ring-color: {{ $empresa->color ?? '#FFD500' }};">
<span class="text-gray-700 text-[13px]">Recordarme</span>
</label>            @if (Route::has('password.request'))                <a href="{{ route('password.request') }}" class="text-[13px] font-semibold" style="color: {{ $empresa->color_secundario ?? $empresa->color ?? '#E6A000' }};">¿Olvidaste tu contraseña?</a>            @endif        </div>
<button type="submit" class="btn btn-primary btn-block w-full py-3" style="background: {{ $empresa->color ?? '#FFD500' }}; color: #000;">            Iniciar Sesión        </button>
</form>
<div class="text-center mt-6 text-[13px] text-gray-500">        ¿No tienes cuenta? <a href="{{ route('register') }}" class="font-semibold" style="color: {{ $empresa->color_secundario ?? $empresa->color ?? '#E6A000' }};">Regístrate aquí</a>
</div>
</x-guest-layout>