<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Notificacion::where('empresa_id', session('empresa_id'))
            ->where(function ($q) use ($user) {
                $q->where('usuario_id', $user->id)->orWhereNull('usuario_id');
            });

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $tipos = Notificacion::where('empresa_id', session('empresa_id'))
            ->select('tipo')
            ->distinct()
            ->orderBy('tipo')
            ->pluck('tipo');

        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('notificaciones.index', compact('notificaciones', 'tipos'));
    }

    public function marcarLeida(Notificacion $notificacione)
    {
        $notificacione->update(['estado' => 'leida']);
        Cache::forget("notificaciones_no_leidas_" . auth()->id());
        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('empresa_id', session('empresa_id'))
            ->where('usuario_id', auth()->id())
            ->where('estado', 'no_leida')
            ->update(['estado' => 'leida']);
        Cache::forget("notificaciones_no_leidas_" . auth()->id());
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}
