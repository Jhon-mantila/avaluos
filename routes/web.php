<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VisitadoresController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\AvaluosController;
use App\Http\Controllers\InformacionVisitaController;

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


Route::resource('visitadores', VisitadoresController::class)->middleware(['auth', 'verified']);
Route::resource('clientes', ClientesController::class)->middleware(['auth', 'verified']);
Route::resource('avaluos', AvaluosController::class)->middleware(['auth', 'verified']);
Route::resource('informacion-visita', InformacionVisitaController::class)->middleware(['auth', 'verified']);