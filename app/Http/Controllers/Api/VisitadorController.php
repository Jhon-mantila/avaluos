<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitadores;
use Illuminate\Support\Facades\Auth;

class VisitadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        /*$visitadores = Visitadores::with('user')->where('active', 1)->get();
        return response()->json($visitadores);*/

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario está autenticado
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // Si el usuario es "admin", devuelve todos los visitadores
        if ($user->hasRole('admin')) {
            $visitadores = Visitadores::with('user')->where('active', 1)->get();
        } 
        // Si el usuario es "visitador", filtrar por su ID
        elseif ($user->hasRole('visitador')) {
            $visitadores = Visitadores::with('user')
                ->where('active', 1)
                ->where('user_id', $user->id) // Filtrar por el ID del usuario autenticado
                ->get();
        } 
        // Si el usuario no tiene un rol válido, retorna un error
        else {
            return response()->json(['message' => 'No tienes permisos para ver esta información'], 403);
        }

        // Retornar los visitadores encontrados
        return response()->json($visitadores);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
