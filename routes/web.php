<?php

use App\Http\Controllers\AseguradoraController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoServicioController;
use App\Http\Controllers\UnidadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('cotizaciones', CotizacionController::class);
    Route::resource('clientes', ClienteController::class);

    Route::resource('aseguradoras', AseguradoraController::class);
    Route::resource('tipos-servicio', TipoServicioController::class)->parameters(['tipos-servicio' => 'tiposServicio']);
    Route::resource('empleados', EmpleadoController::class);
    Route::resource('operadores', OperadorController::class);
    Route::resource('unidades', UnidadController::class);
    Route::resource('convenios', ConvenioController::class);
    Route::resource('servicios', ServicioController::class);

    Route::get('notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::patch('notificaciones/{notificacione}/leer', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leer');
    Route::post('notificaciones/leer-todas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.leer-todas');

    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::put('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
});

require __DIR__.'/auth.php';
