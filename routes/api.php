<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\AvaluoController;
use App\Http\Controllers\Api\VisitadorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InformacionVisitaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/avaluos', [AvaluoController::class, 'index']);
Route::get('/visitadores', [VisitadorController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/informacion-visitas', [InformacionVisitaController::class, 'index']);