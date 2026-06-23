<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $empresa = Empresa::findOrFail(session('empresa_id'));
        return view('configuracion.index', compact('empresa'));
    }

    public function update(Request $request)
    {
        $this->authorize('admin');
        $empresa = Empresa::findOrFail(session('empresa_id'));

        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'color' => 'nullable|string|max:20',
            'color_secundario' => 'nullable|string|max:20',
            'tipografia' => 'required|string|max:100',
            'modo_oscuro' => 'boolean',
            'telefono_contacto' => 'nullable|string|max:30',
            'email_contacto' => 'nullable|email|max:150',
            'whatsapp' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'moneda' => 'required|string|max:10',
            'formato_fecha' => 'required|string|max:20',
            'zona_horaria' => 'required|string|max:50',
            'idioma' => 'required|string|max:5',
            'footer_texto' => 'nullable|string|max:255',
            'mostrar_precios' => 'boolean',
            'notificaciones_habilitadas' => 'boolean',
        ]);

        $data['modo_oscuro'] = $request->boolean('modo_oscuro');
        $data['mostrar_precios'] = $request->boolean('mostrar_precios');
        $data['notificaciones_habilitadas'] = $request->boolean('notificaciones_habilitadas');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('empresas', 'public');
        }
        if ($request->hasFile('logo_oscuro')) {
            $data['logo_oscuro'] = $request->file('logo_oscuro')->store('empresas', 'public');
        }
        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('empresas', 'public');
        }

        $empresa->update($data);

        return redirect()->route('configuracion.index')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}
