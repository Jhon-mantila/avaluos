<?php

namespace App\Http\Controllers;
use App\Models\Avaluos;
use App\Models\Clientes;
use Inertia\Inertia;

use Illuminate\Http\Request;

class AvaluosController extends Controller
{
    //
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->query('search');

        // Consulta base
        $query = Avaluos::with('cliente'); // Incluir la relación con el cliente

        // Aplicar búsqueda si hay un término
        if ($search) {
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%")
                  ->orWhereHas('cliente', function ($q) use ($search) {
                      $q->where('nombre', 'LIKE', "%{$search}%");
                  });
        }
        $query->orderBy('created_at', 'desc');
        // Paginar los resultados y conservar el parámetro de búsqueda
        $avaluos = $query->paginate(10)->appends(['search' => $search]);
        //dd($avaluos);
        // Retornar la vista de Inertia con los datos
        return Inertia::render('Avaluos/Index', [
            'avaluos' => $avaluos,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        $clientes = Clientes::all();
        return Inertia::render('Avaluos/Create', [
            'clientes' => $clientes,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numero_avaluo' => 'required|string|max:255',
            'cliente_id' => 'required|exists:clientes,id',
            'estado' => 'required|string|max:255',
        ]);

        Avaluos::create($validatedData);

        return redirect()->route('avaluos.index')->with('success', 'Avalúo creado correctamente.');
    }
}
