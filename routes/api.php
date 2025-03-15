<?php

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\AvaluoController;
use App\Http\Controllers\Api\VisitadorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InformacionVisitaController;
use App\Http\Controllers\Api\RegistroFotograficoController;
use App\Http\Controllers\Api\PlantillaController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::get('/avaluos', [AvaluoController::class, 'index']);
    Route::get('/visitadores', [VisitadorController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/informacion-visitas', [InformacionVisitaController::class, 'index']);
    
    Route::post('/registros-fotograficos', [RegistroFotograficoController::class, 'store']);
    Route::get('/plantillas/{id}/imagenes', [PlantillaController::class, 'getImages']);
    Route::post('/imagenes/update/{id}', [RegistroFotograficoController::class, 'update']);
    Route::post('/imagenes/update-order', [RegistroFotograficoController::class, 'updateOrder']);
    Route::delete('/imagenes/{id}', [RegistroFotograficoController::class, 'destroy']);

})->middleware('auth:sanctum');*/

/*Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


});

Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/avaluos', [AvaluoController::class, 'index']);
Route::get('/visitadores', [VisitadorController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/informacion-visitas', [InformacionVisitaController::class, 'index']);

Route::post('/registros-fotograficos', [RegistroFotograficoController::class, 'store']);
Route::get('/plantillas/{id}/imagenes', [PlantillaController::class, 'getImages']);
Route::post('/imagenes/update/{id}', [RegistroFotograficoController::class, 'update']);
Route::post('/imagenes/update-order', [RegistroFotograficoController::class, 'updateOrder']);
Route::delete('/imagenes/{id}', [RegistroFotograficoController::class, 'destroy']);*/
// Ruta para iniciar sesión y obtener el token
Route::post('/login', function (Request $request) {
    // Buscar el usuario por su email
    $user = User::where('email', $request->email)->first();

    // Verificar si el usuario existe y la contraseña es correcta
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }
    // Expiración en 1 hora
    $expiresAt = Carbon::now()->addHour();
    // Generar un token de acceso para el usuario autenticado
    $token = $user->createToken('auth_token')->plainTextToken;
    // Guardar la expiración manualmente
    $user->tokens()->latest()->first()->update(['expires_at' => $expiresAt]);
    // Retornar el token y los roles del usuario
    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'expires_at' => $expiresAt, // Devolver la fecha de expiración
        'roles' => $user->roles->pluck('name'), // Envía los roles del usuario
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::get('/avaluos', [AvaluoController::class, 'index']);
    Route::get('/visitadores', [VisitadorController::class, 'index']);
    Route::get('/informacion-visitas', [InformacionVisitaController::class, 'index']);
    

});
Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);


Route::post('/registros-fotograficos', [RegistroFotograficoController::class, 'store']);
Route::get('/plantillas/{id}/imagenes', [PlantillaController::class, 'getImages']);
Route::post('/imagenes/update/{id}', [RegistroFotograficoController::class, 'update']);
Route::post('/imagenes/update-order', [RegistroFotograficoController::class, 'updateOrder']);
Route::delete('/imagenes/{id}', [RegistroFotograficoController::class, 'destroy']);
