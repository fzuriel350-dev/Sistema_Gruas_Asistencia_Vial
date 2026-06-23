<x-guest-layout>
<div class="mb-4 text-sm text-gray-600">        Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.    </div>
<form method="POST" action="{{ route('password.confirm') }}">        @csrf        <div class="form-group">
<label for="password">Contraseña</label>
<input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
<x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
<button type="submit" class="btn btn-primary btn-block w-full py-3 mt-2">            Confirmar        </button>
</form>
</x-guest-layout>
