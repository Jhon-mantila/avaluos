<?php

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\AvaluoController;
use App\Http\Controllers\Api\VisitadorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InformacionVisitaController;
use App\Http\Controllers\Api\RegistroFotograficoController;
use App\Http\Controllers\Api\PlantillaController;


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
//Route::delete('/imagenes/delete-multiple', [RegistroFotograficoController::class, 'destroyMultiple']);
//Route::match(['post', 'delete'], '/imagenes/delete-multiple', [RegistroFotograficoController::class, 'destroyMultiple']);
//Route::delete('/imagenes/delete-multiple', [RegistroFotograficoController::class, 'destroyMultiple']);
Route::post('/imagenes/delete-multiple', [RegistroFotograficoController::class, 'destroyMultiple']);

//Api para departamentos y municipios
Route::get('/departamentos', function () {
    return DB::table('departamentos')->select('id', 'nombre')->get();
});

Route::get('/municipios/{departamento_id}', function ($departamento_id) {
    return DB::table('municipios')
        ->where('departamento_id', $departamento_id)
        ->select('id', 'nombre')
        ->get();
});