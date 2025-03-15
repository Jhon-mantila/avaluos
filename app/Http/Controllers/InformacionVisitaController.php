<?php

namespace App\Http\Controllers;
use App\Models\InformacionVisita;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class InformacionVisitaController extends Controller
{
    //
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->query('search');

        // Consulta base con las relaciones `avaluo` y `visitador.user`
        $query = InformacionVisita::with(['avaluo', 'visitador.user']);

        // Filtrar por rol de usuario
        if (Auth::user()->hasRole('visitador')) {
            $query->whereHas('visitador.user', function ($q) {
                $q->where('id', Auth::id());
            });
        }

        // Ordenar los resultados en orden descendente por la columna 'created_at'
        $query->orderBy('created_at', 'desc');
        // Aplicar búsqueda si hay un término
        if ($search) {
            $query->whereHas('avaluo', function ($q) use ($search) {
                $q->where('numero_avaluo', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('visitador.user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            })
            ->orWhere('ciudad', 'LIKE', "%{$search}%");
        }

        // Paginar los resultados y conservar el parámetro de búsqueda
        $informacionVisitas = $query->paginate(10)->appends(['search' => $search]);
        //dd($informacionVisitas);
        // Retornar la vista de Inertia con los datos
        return Inertia::render('InformacionVisitas/Index', [
            'informacionVisitas' => $informacionVisitas,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show($id)
    {
        $visita = InformacionVisita::with(['avaluo', 'visitador.user'])->findOrFail($id);

        return Inertia::render('InformacionVisitas/Show', [
            'visita' => $visita,
        ]);
    }

    public function create()
    {
        // Obtener los datos necesarios para la vista de creación
        return inertia('InformacionVisitas/Create');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'avaluo_id' => 'required|exists:avaluos,id',
            'visitador_id' => 'required|exists:visitadores,id',
            'celular' => 'required|string|max:15',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'fecha_visita' => 'required|date',
            'observaciones' => 'nullable|string|max:255',
        ]);

        // Crear una nueva información de visita
        InformacionVisita::create($validatedData);

        return redirect()->route('informacion-visita.index')->with('success', 'Información de visita creada correctamente.');
    }

    public function edit($id)
    {
        $informacionVisita = InformacionVisita::findOrFail($id);
        return inertia('InformacionVisitas/Edit', [
            'informacionVisita' => $informacionVisita,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'avaluo_id' => 'required|exists:avaluos,id',
            'visitador_id' => 'required|exists:visitadores,id',
            'celular' => 'required|string|max:15',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'fecha_visita' => 'required|date',
            'observaciones' => 'nullable|string|max:255',
        ]);

        // Actualizar la información de visita
        $informacionVisita = InformacionVisita::findOrFail($id);
        $informacionVisita->update($validatedData);

        return redirect()->route('informacion-visita.index')->with('success', 'Información de visita actualizada correctamente.');
    }
}
