<x-guest-layout>
<form method="POST" action="{{ route('password.store') }}">        @csrf        <input type="hidden" name="token" value="{{ $request->route('token') }}">
<div class="form-group">
<label for="email">Correo electrónico</label>
<input id="email" type="email" name="email" :value="old('email', $request->email)" placeholder="tu@correo.com" required autofocus autocomplete="username">
<x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
<div class="form-group">
<label for="password">Nueva contraseña</label>
<input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="new-password">
<x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
<div class="form-group">
<label for="password_confirmation">Confirmar contraseña</label>
<input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
<x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>
<button type="submit" class="btn btn-primary btn-block w-full py-3 mt-2">            Restablecer contraseña        </button>
</form>
</x-guest-layout>
