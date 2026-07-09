<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 1. Obtener el usuario que acaba de iniciar sesión
        $user = auth()->user();

        // 2. Redirección específica basándose en la columna 'role' del modelo User
        if ($user->role === 'admin') {
            return redirect()->intended(route('dashboard'));
        } 
        
        if ($user->role === 'cotizador') {
            return redirect()->intended(route('cotizaciones.index'));
        } 
        
        if ($user->role === 'cliente') {
            return redirect()->intended(route('clientes.servicios'));
        } 
        
        if ($user->role === 'operador') {
            return redirect()->intended(route('operadores.index'));
        }

        // Redirección por defecto si el rol no coincide con ninguno de los anteriores
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Forzar redirección limpia a la pantalla de login al salir
        return redirect('/login');
    }
}