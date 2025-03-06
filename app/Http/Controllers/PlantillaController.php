<?php

namespace App\Http\Controllers;
use App\Models\Plantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PlantillaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->query('search');

        // Consulta base
        $query = Plantilla::with(['informacionVisita.avaluo']); // Incluir la relación con la información de la visita y el avalúo;
        // Ordenar los resultados en orden descendente por la columna 'created_at'
        $query->orderBy('created_at', 'desc');
        // Aplicar búsqueda si hay un término
        if ($search) {
            $query->where('nombre_plantilla', 'LIKE', "%{$search}%");
        }
        
        // Paginar los resultados y conservar el parámetro de búsqueda
        $plantillas = $query->paginate(10)->appends(['search' => $search]);

        // Retornar la vista de Inertia con los datos
        return Inertia::render('Plantillas/Index', [
            'plantillas' => $plantillas,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        // Obtener los datos necesarios para la vista de creación
        return Inertia::render('Plantillas/Create');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre_plantilla' => 'required|string|max:255',
            'informacion_visita_id' => 'required|exists:informacion_visitas,id',
        ]);
        // Agregar el ID del usuario autenticado
        $validatedData['user_id'] = Auth::id();
        // Crear una nueva plantilla
        Plantilla::create($validatedData);

        return redirect()->route('plantillas.index')->with('success', 'Plantilla creada correctamente.');
    }
}
