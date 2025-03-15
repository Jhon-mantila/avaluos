<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InformacionVisita;
use Illuminate\Support\Facades\Auth;

class InformacionVisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar el rol del usuario
        if ($user->hasRole('admin')) {
            // Si es admin, devolver todas las visitas
            $informacionVisitas = InformacionVisita::with(['avaluo' => function($query) {
                $query->whereNotIn('estado', ['Completado', 'Cancelado']);
            }])->get();
        } elseif ($user->hasRole('visitador')) {
            // Si es visitador, devolver solo las visitas relacionadas con el usuario logueado
            $informacionVisitas = InformacionVisita::with(['avaluo' => function($query) {
                $query->whereNotIn('estado', ['Completado', 'Cancelado']);
            }])->whereHas('visitador.user', function ($query) use ($user) {
                $query->where('id', $user->id);
            })->get();
        } else {
            // Si no tiene ninguno de los roles, devolver un error 403
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json($informacionVisitas);
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
