<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitadores;
use App\Models\InformacionVisita;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class VisitadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->query('search');

        // Consulta base con la relación `user`
        $query = Visitadores::with('user');
        // Ordenar los resultados en orden descendente por la columna 'created_at'
        $query->orderBy('created_at', 'desc');
        // Aplicar búsqueda si hay un término
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->orWhere('ciudad', 'LIKE', "%{$search}%");
        }

        $id = Auth::id(); // Obtiene solo el ID del usuario autenticado
        $user = Auth::user(); // Obtiene todos los datos del usuario autenticado
        //dd($id, $user );
        // Paginar los resultados
        //$visitadores = $query->paginate(10);
        // Paginar los resultados y conservar el parámetro de búsqueda
        $visitadores = $query->paginate(10)->appends(['search' => $search]);

        // Retornar la vista de Inertia con los datos
        return Inertia::render('Visitadores/Index', [
            'visitadores' => $visitadores,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener los datos necesarios para la vista de creación
        return inertia('Visitadores/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'user_id' => 'exists:users,id',
            'telefono' => 'required|string|max:15',
            'ciudad' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'cobertura' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        // Crear un nuevo visitador
        Visitadores::create($validatedData);

        return redirect()->route('visitadores.index')->with('success', 'Visitador creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $visitador = Visitadores::with('user')->findOrFail($id);
        $visitas = InformacionVisita::where('visitador_id', $id)->with('avaluo')->paginate(5);
        return Inertia::render('Visitadores/Show', [
            'visitador' => $visitador,
            'visitas' => $visitas,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
