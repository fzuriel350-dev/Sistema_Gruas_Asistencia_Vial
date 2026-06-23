<x-guest-layout>
<form method="POST" action="{{ route('register') }}">        @csrf        <div class="form-group">
<label for="name">Nombre completo</label>
<input id="name" type="text" name="name" :value="old('name')" placeholder="Tu nombre" required autofocus autocomplete="name">
<x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>
<div class="form-group">
<label for="email">Correo electrónico</label>
<input id="email" type="email" name="email" :value="old('email')" placeholder="tu@correo.com" required autocomplete="username">
<x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>
<div class="form-group">
<label for="phone">Teléfono</label>
<input id="phone" type="text" name="phone" :value="old('phone')" placeholder="55 1234 5678" required>
<x-input-error :messages="$errors->get('phone')" class="mt-2" />
</div>
<div class="form-group">
<label for="aseguradora">Aseguradora</label>
<select id="aseguradora" name="aseguradora" required>
<option value="">Selecciona tu aseguradora</option>
<option value="Quálitas" {{ old('aseguradora') == 'Quálitas' ? 'selected' : '' }}>Quálitas</option>
<option value="GNP Seguros" {{ old('aseguradora') == 'GNP Seguros' ? 'selected' : '' }}>GNP Seguros</option>
<option value="AXA Seguros" {{ old('aseguradora') == 'AXA Seguros' ? 'selected' : '' }}>AXA Seguros</option>
<option value="BBVA Seguros" {{ old('aseguradora') == 'BBVA Seguros' ? 'selected' : '' }}>BBVA Seguros</option>
<option value="Mapfre" {{ old('aseguradora') == 'Mapfre' ? 'selected' : '' }}>Mapfre</option>
<option value="Otra" {{ old('aseguradora') == 'Otra' ? 'selected' : '' }}>Otra</option>
</select>
<x-input-error :messages="$errors->get('aseguradora')" class="mt-2" />
</div>
<div class="form-group">
<label for="password">Contraseña</label>
<input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="new-password">
<x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
<div class="form-group">
<label for="password_confirmation">Confirmar contraseña</label>
<input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
<x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>
<button type="submit" class="btn btn-primary btn-block w-full py-3 mt-2">            Crear cuenta        </button>
</form>
<div class="text-center mt-6 text-[13px] text-gray-500">        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="font-semibold" style="color: #E6A000;">Inicia sesión</a>
</div>
</x-guest-layout>
