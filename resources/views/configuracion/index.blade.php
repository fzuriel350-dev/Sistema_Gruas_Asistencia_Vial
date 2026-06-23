@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="max-w-4xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('configuracion.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="card">
            <div class="card-header"><h3>Identidad de la Empresa</h3></div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nombre de la empresa</label>
                        <input name="nombre" value="{{ old('nombre', $empresa->nombre) }}" required>
                        @error('nombre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>Eslogan / Footer</label>
                        <input name="footer_texto" value="{{ old('footer_texto', $empresa->footer_texto) }}" placeholder="Texto opcional en el pie">
                        @error('footer_texto') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>Logo claro</label>
                        <input type="file" name="logo" accept="image/*">
                        @if ($empresa->logo)
                            <div class="mt-2"><img src="{{ Storage::url($empresa->logo) }}" class="h-10"></div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Logo oscuro</label>
                        <input type="file" name="logo_oscuro" accept="image/*">
                        @if ($empresa->logo_oscuro)
                            <div class="mt-2"><img src="{{ Storage::url($empresa->logo_oscuro) }}" class="h-10"></div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Favicon</label>
                        <input type="file" name="favicon" accept="image/*">
                        @if ($empresa->favicon)
                            <div class="mt-2"><img src="{{ Storage::url($empresa->favicon) }}" class="h-8"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>Personalización Visual</h3></div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Color primario</label>
                        <input name="color" type="color" value="{{ old('color', $empresa->color ?? '#FFD500') }}">
                        @error('color') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>Color secundario</label>
                        <input name="color_secundario" type="color" value="{{ old('color_secundario', $empresa->color_secundario ?? '#E6A000') }}">
                        @error('color_secundario') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>Tipografía</label>
                        <select name="tipografia">
                            <option value="Inter" @selected(old('tipografia', $empresa->tipografia) === 'Inter')>Inter</option>
                            <option value="Poppins" @selected(old('tipografia', $empresa->tipografia) === 'Poppins')>Poppins</option>
                            <option value="Roboto" @selected(old('tipografia', $empresa->tipografia) === 'Roboto')>Roboto</option>
                            <option value="Open Sans" @selected(old('tipografia', $empresa->tipografia) === 'Open Sans')>Open Sans</option>
                            <option value="Lato" @selected(old('tipografia', $empresa->tipografia) === 'Lato')>Lato</option>
                            <option value="Montserrat" @selected(old('tipografia', $empresa->tipografia) === 'Montserrat')>Montserrat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="flex items-center gap-2 pt-5">
                            <input type="checkbox" name="modo_oscuro" value="1" @checked(old('modo_oscuro', $empresa->modo_oscuro))>
                            <span class="text-sm font-medium">Modo oscuro predeterminado</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>Información de Contacto</h3></div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Teléfono de contacto</label>
                        <input name="telefono_contacto" value="{{ old('telefono_contacto', $empresa->telefono_contacto) }}">
                        @error('telefono_contacto') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>Email de contacto</label>
                        <input name="email_contacto" type="email" value="{{ old('email_contacto', $empresa->email_contacto) }}">
                        @error('email_contacto') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>WhatsApp</label>
                        <input name="whatsapp" value="{{ old('whatsapp', $empresa->whatsapp) }}" placeholder="5215512345678">
                        @error('whatsapp') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label>Sitio web</label>
                        <input name="sitio_web" type="url" value="{{ old('sitio_web', $empresa->sitio_web) }}" placeholder="https://">
                        @error('sitio_web') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group full-width">
                        <label>Dirección</label>
                        <input name="direccion" value="{{ old('direccion', $empresa->direccion) }}">
                        @error('direccion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>Regionalización</h3></div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Moneda</label>
                        <select name="moneda">
                            <option value="$" @selected(old('moneda', $empresa->moneda) === '$')>$ — Peso mexicano</option>
                            <option value="USD$" @selected(old('moneda', $empresa->moneda) === 'USD$')>USD$ — Dólar</option>
                            <option value="€" @selected(old('moneda', $empresa->moneda) === '€')>€ — Euro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Formato de fecha</label>
                        <select name="formato_fecha">
                            <option value="d/m/Y" @selected(old('formato_fecha', $empresa->formato_fecha) === 'd/m/Y')>31/12/2026</option>
                            <option value="m/d/Y" @selected(old('formato_fecha', $empresa->formato_fecha) === 'm/d/Y')>12/31/2026</option>
                            <option value="Y-m-d" @selected(old('formato_fecha', $empresa->formato_fecha) === 'Y-m-d')>2026-12-31</option>
                            <option value="d/m/y" @selected(old('formato_fecha', $empresa->formato_fecha) === 'd/m/y')>31/12/26</option>
                            <option value="j M Y" @selected(old('formato_fecha', $empresa->formato_fecha) === 'j M Y')>31 Dic 2026</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Zona horaria</label>
                        <select name="zona_horaria">
                            <option value="America/Mexico_City" @selected(old('zona_horaria', $empresa->zona_horaria) === 'America/Mexico_City')>Ciudad de México (UTC-6)</option>
                            <option value="America/Monterrey" @selected(old('zona_horaria', $empresa->zona_horaria) === 'America/Monterrey')>Monterrey (UTC-6)</option>
                            <option value="America/Tijuana" @selected(old('zona_horaria', $empresa->zona_horaria) === 'America/Tijuana')>Tijuana (UTC-8)</option>
                            <option value="America/Argentina/Buenos_Aires" @selected(old('zona_horaria', $empresa->zona_horaria) === 'America/Argentina/Buenos_Aires')>Buenos Aires (UTC-3)</option>
                            <option value="America/Bogota" @selected(old('zona_horaria', $empresa->zona_horaria) === 'America/Bogota')>Bogotá (UTC-5)</option>
                            <option value="America/Santiago" @selected(old('zona_horaria', $empresa->zona_horaria) === 'America/Santiago')>Santiago (UTC-4)</option>
                            <option value="America/Lima" @selected(old('zona_horaria', $empresa->zona_horaria) === 'America/Lima')>Lima (UTC-5)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Idioma</label>
                        <select name="idioma">
                            <option value="es" @selected(old('idioma', $empresa->idioma) === 'es')>Español</option>
                            <option value="en" @selected(old('idioma', $empresa->idioma) === 'en')>English</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>Preferencias del Sistema</h3></div>
            <div class="card-body">
                <div class="space-y-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="mostrar_precios" value="1" @checked(old('mostrar_precios', $empresa->mostrar_precios)) class="rounded border-gray-300">
                        <div>
                            <span class="text-sm font-medium">Mostrar precios a clientes</span>
                            <p class="text-xs text-gray-500">Si está desactivado, los clientes no verán montos en cotizaciones.</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="notificaciones_habilitadas" value="1" @checked(old('notificaciones_habilitadas', $empresa->notificaciones_habilitadas)) class="rounded border-gray-300">
                        <div>
                            <span class="text-sm font-medium">Notificaciones habilitadas</span>
                            <p class="text-xs text-gray-500">Los usuarios recibirán notificaciones del sistema.</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 justify-end">
            <button type="submit" class="btn btn-primary px-8">Guardar configuración</button>
        </div>
    </form>
</div>
@endsection
