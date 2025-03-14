<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VisitadoresController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\AvaluosController;
use App\Http\Controllers\InformacionVisitaController;
use App\Http\Controllers\PlantillaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/debug-role', function () {
        return response()->json([
            'usuario' => auth()->user(),
            'roles' => auth()->user()->getRoleNames(),
            'tiene_admin' => auth()->user()->hasRole('admin'),
        ]);
    });
});

/*Route::resource('visitadores', VisitadoresController::class)->middleware(['auth', 'verified']);
Route::resource('clientes', ClientesController::class)->middleware(['auth', 'verified']);
Route::resource('avaluos', AvaluosController::class)->middleware(['auth', 'verified']);
Route::resource('informacion-visita', InformacionVisitaController::class)->middleware(['auth', 'verified']);
Route::resource('plantillas', PlantillaController::class)->middleware(['auth', 'verified']);
Route::get('/plantillas/{id}/pdf', [PDFController::class, 'generarPDF']);*/

// Rutas para administradores
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('visitadores', VisitadoresController::class);
    Route::resource('clientes', ClientesController::class);
    Route::resource('avaluos', AvaluosController::class);
    Route::resource('informacion-visita', InformacionVisitaController::class);
    Route::resource('plantillas', PlantillaController::class);
    Route::get('/plantillas/{id}/pdf', [PDFController::class, 'generarPDF']);
    Route::resource('users', UserController::class);
});

// Rutas para visitadores
Route::middleware(['auth', 'verified', 'role_or_permission:visitador|informacion-visita.index|plantillas.index'])->group(function () {
    Route::resource('informacion-visita', InformacionVisitaController::class);
    Route::resource('plantillas', PlantillaController::class);
    Route::get('/plantillas/{id}/pdf', [PDFController::class, 'generarPDF']);
});