<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $facturas = Factura::where('empresa_id', session('empresa_id'))
            ->with('cliente', 'servicio.cotizacion')
            ->orderByDesc('id')
            ->paginate(15);
        return view('facturas.index', compact('facturas'));
    }

    public function show(Factura $factura)
    {
        $this->authorize('empleado');
        $factura->load('cliente', 'servicio.tipoServicio', 'servicio.cotizacion.cliente', 'servicio.cotizacion.aseguradora', 'servicio.operador.empleado');
        return view('facturas.show', compact('factura'));
    }

    public function descargarPdf(Factura $factura)
    {
        $this->authorize('empleado');

        $factura->load('cliente', 'servicio.tipoServicio', 'servicio.cotizacion.aseguradora', 'empresa');
        $empresa = $factura->empresa;

        $pdf = Pdf::loadView('facturas.pdf', compact('factura', 'empresa'))
            ->setPaper('letter')
            ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download('factura_' . $factura->folio_factura . '.pdf');
    }

    public function destroy(Factura $factura)
    {
        $this->authorize('admin');
        $factura->delete();
        return redirect()->route('facturas.index')->with('success', 'Factura eliminada.');
    }
}
