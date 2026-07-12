<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 12px; line-height: 1.4; }

    .page { padding: 30px 35px; }

    .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 18px; margin-bottom: 22px; border-bottom: 3px solid {{ $empresa->color ?? '#FFD500' }}; }
    .header-left { width: 62%; }
    .header-right { width: 35%; text-align: right; }
    .empresa-nombre { font-size: 20px; font-weight: 800; color: {{ $empresa->color ?? '#FFD500' }}; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px; }
    .empresa-datos { font-size: 10px; color: #666; line-height: 1.6; }
    .factura-badge { display: inline-block; background: {{ $empresa->color ?? '#FFD500' }}; color: #333; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; padding: 6px 18px; border-radius: 4px; margin-bottom: 8px; }
    .factura-folio { font-size: 18px; font-weight: 800; color: #222; }
    .factura-fecha { font-size: 10px; color: #888; margin-top: 3px; }

    .info-boxes { display: flex; gap: 15px; margin-bottom: 22px; }
    .info-box { flex: 1; background: #f9f9f9; border: 1px solid #eee; border-radius: 6px; padding: 12px 14px; }
    .info-box-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: #999; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 3px; }
    .info-label { font-size: 9.5px; color: #888; text-transform: uppercase; letter-spacing: 0.3px; }
    .info-value { font-size: 11px; font-weight: 600; color: #333; }

    .table-section { margin-bottom: 22px; }
    table { width: 100%; border-collapse: collapse; }
    thead th { background: {{ $empresa->color ?? '#FFD500' }}; color: #333; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; padding: 9px 12px; text-align: left; }
    thead th:last-child { text-align: right; }
    tbody td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-size: 11.5px; }
    tbody td:last-child { text-align: right; font-weight: 600; }
    tbody tr:nth-child(even) { background: #fafafa; }
    tbody tr:last-child td { border-bottom: 2px solid #ddd; }

    .totals { display: flex; justify-content: flex-end; margin-bottom: 25px; }
    .totals-table { width: 260px; }
    .totals-table tr td { padding: 4px 0; font-size: 11px; }
    .totals-table tr td:first-child { color: #666; }
    .totals-table tr td:last-child { text-align: right; font-weight: 600; }
    .totals-separator { border-top: 1px solid #ccc; }
    .totals-total td { border-top: 2px solid #333; padding-top: 6px !important; font-size: 15px !important; font-weight: 800 !important; color: #222; }

    .notes { background: #fffbea; border: 1px solid #f5e6a3; border-radius: 6px; padding: 10px 14px; margin-bottom: 22px; }
    .notes-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin-bottom: 4px; }
    .notes-text { font-size: 10.5px; color: #555; }

    .footer { border-top: 2px solid {{ $empresa->color ?? '#FFD500' }}; padding-top: 12px; text-align: center; }
    .footer-text { font-size: 9px; color: #999; line-height: 1.5; }
    .footer-text strong { color: #666; }
</style>
</head>
<body>

<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            @if($empresa->logo)
                <div style="margin-bottom:10px;">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $empresa->logo))) }}" style="max-height:45px;">
                </div>
            @endif
            <div class="empresa-nombre">{{ $empresa->nombre }}</div>
            <div class="empresa-datos">
                @if($empresa->direccion){{ $empresa->direccion }}<br>@endif
                @if($empresa->telefono_contacto)Tel: {{ $empresa->telefono_contacto }}@endif
                @if($empresa->email_contacto) &nbsp;|&nbsp; {{ $empresa->email_contacto }}@endif
                @if($empresa->whatsapp)<br>WhatsApp: {{ $empresa->whatsapp }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="factura-badge">Factura</div>
            <div class="factura-folio">{{ $factura->folio_factura }}</div>
            <div class="factura-fecha">Fecha de emisión: {{ $factura->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- INFO BOXES --}}
    <div class="info-boxes">
        <div class="info-box">
            <div class="info-box-title">Datos del Cliente</div>
            <div class="info-row">
                <span class="info-label">Nombre</span>
                <span class="info-value">{{ $factura->cliente?->nombre ?? '—' }}</span>
            </div>
            @if($factura->cliente?->correo)
            <div class="info-row">
                <span class="info-label">Correo</span>
                <span class="info-value">{{ $factura->cliente->correo }}</span>
            </div>
            @endif
            @if($factura->cliente?->telefono)
            <div class="info-row">
                <span class="info-label">Teléfono</span>
                <span class="info-value">{{ $factura->cliente->telefono }}</span>
            </div>
            @endif
            @if($factura->servicio?->cotizacion?->aseguradora)
            <div class="info-row">
                <span class="info-label">Aseguradora</span>
                <span class="info-value">{{ $factura->servicio->cotizacion->aseguradora->nombre }}</span>
            </div>
            @endif
        </div>
        <div class="info-box">
            <div class="info-box-title">Datos del Servicio</div>
            <div class="info-row">
                <span class="info-label">Folio servicio</span>
                <span class="info-value">#{{ $factura->servicio?->id ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tipo de servicio</span>
                <span class="info-value">{{ $factura->servicio?->tipoServicio?->nombre ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Cotización</span>
                <span class="info-value">{{ $factura->servicio?->cotizacion?->folio ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Fecha del servicio</span>
                <span class="info-value">{{ $factura->servicio?->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
            </div>
            @if($factura->servicio?->kms_cobrados_reales)
            <div class="info-row">
                <span class="info-label">Kilómetros</span>
                <span class="info-value">{{ $factura->servicio->kms_cobrados_reales }} km</span>
            </div>
            @endif
        </div>
    </div>

    {{-- TABLA DE CONCEPTOS --}}
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Concepto</th>
                    <th>Detalle</th>
                    <th style="text-align:right">Importe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Servicio de grúa — {{ $factura->servicio?->tipoServicio?->nombre ?? 'N/A' }}</td>
                    <td>{{ $factura->servicio?->cotizacion?->folio ?? '' }}</td>
                    <td>${{ number_format($factura->subtotal - ($factura->servicio?->cargos_extras ?? 0), 2) }}</td>
                </tr>
                @if(($factura->servicio?->cargos_extras ?? 0) > 0)
                <tr>
                    <td>2</td>
                    <td>Cargo extra por kilómetros excedidos</td>
                    <td>{{ $factura->servicio?->motivo_cargos_extras ?? '' }}</td>
                    <td>${{ number_format($factura->servicio->cargos_extras, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- TOTALES --}}
    <div class="totals">
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td>${{ number_format($factura->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>IVA (16%)</td>
                <td>${{ number_format($factura->iva, 2) }}</td>
            </tr>
            <tr class="totals-separator">
                <td></td>
                <td></td>
            </tr>
            <tr class="totals-total">
                <td>Total</td>
                <td>${{ number_format($factura->total, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-text">
            <strong>{{ $empresa->nombre }}</strong><br>
            {{ $empresa->footer_texto ?? 'Gracias por su preferencia' }}
            @if($empresa->sitio_web)<br>{{ $empresa->sitio_web }}@endif
        </div>
    </div>

</div>

</body>
</html>
