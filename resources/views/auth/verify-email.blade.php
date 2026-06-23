<x-guest-layout>
<div class="mb-4 text-sm text-gray-600">        Gracias por registrarte. Antes de empezar, verifica tu correo electrónico haciendo clic en el enlace que te enviamos.    </div>    @if (session('status') == 'verification-link-sent')        <div class="mb-4 font-medium text-sm text-emerald-600">        Se ha enviado un nuevo enlace de verificación al correo que registraste.        </div>    @endif    <div class="mt-4 flex flex-col gap-3">
<form method="POST" action="{{ route('verification.send') }}">            @csrf            <button type="submit" class="btn btn-primary btn-block w-full py-3">                Reenviar correo de verificación            </button>
</form>
<form method="POST" action="{{ route('logout') }}">            @csrf            <button type="submit" class="btn btn-secondary btn-block w-full py-3">                Cerrar sesión            </button>
</form>
</div>
</x-guest-layout>
