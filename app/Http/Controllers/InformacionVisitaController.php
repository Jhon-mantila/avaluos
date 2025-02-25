<?php

namespace App\Http\Controllers;
use App\Models\InformacionVisita;
use Illuminate\Http\Request;
use Inertia\Inertia;
class InformacionVisitaController extends Controller
{
    //
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->query('search');

        // Consulta base con las relaciones `avaluo` y `visitador.user`
        $query = InformacionVisita::with(['avaluo', 'visitador.user']);

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
}
