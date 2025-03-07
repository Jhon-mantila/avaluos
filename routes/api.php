<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\AvaluoController;
use App\Http\Controllers\Api\VisitadorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InformacionVisitaController;
use App\Http\Controllers\Api\RegistroFotograficoController;
use App\Http\Controllers\Api\PlantillaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/avaluos', [AvaluoController::class, 'index']);
Route::get('/visitadores', [VisitadorController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/informacion-visitas', [InformacionVisitaController::class, 'index']);

Route::post('/registros-fotograficos', [RegistroFotograficoController::class, 'store']);
Route::post('/imagenes/update-order', [RegistroFotograficoController::class, 'updateOrder']);
Route::delete('/imagenes/{id}', [RegistroFotograficoController::class, 'destroy']);
Route::get('/plantillas/{id}/imagenes', [PlantillaController::class, 'getImages']);