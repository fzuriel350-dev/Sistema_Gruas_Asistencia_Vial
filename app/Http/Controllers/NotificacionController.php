<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notificaciones = Notificacion::where('empresa_id', session('empresa_id'))
            ->where(function ($q) use ($user) {
                $q->where('usuario_id', $user->id)->orWhereNull('usuario_id');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida(Notificacion $notificacione)
    {
        $notificacione->update(['estado' => 'leida']);
        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('empresa_id', session('empresa_id'))
            ->where('usuario_id', auth()->id())
            ->where('estado', 'no_leida')
            ->update(['estado' => 'leida']);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}
