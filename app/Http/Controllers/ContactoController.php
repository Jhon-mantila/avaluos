<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use Inertia\Inertia;
use App\Services\DropdownService;

class ContactoController extends Controller
{
    //
    protected $dropdownService;

    public function __construct(DropdownService $dropdownService)
    {
        $this->dropdownService = $dropdownService;
    }
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->query('search');

        // Consulta base 
        $query = Contacto::query();

        // Aplicar búsqueda si hay un término
        if ($search) {
            $query->where('nombre', 'LIKE', "%{$search}%")
            ->orWhere('celular', 'LIKE', "%{$search}%");
        }
        // Ordenar los resultados en orden descendente por la columna 'created_at'
        $query->orderBy('created_at', 'desc');

        // Paginar los resultados
        // Paginar los resultados y conservar el parámetro de búsqueda
        $contactos = $query->paginate(10)->appends(['search' => $search]);

        // Retornar la vista de Inertia con los datos
        return Inertia::render('Contactos/Index', [
            'contactos' => $contactos,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(string $id)
    {
        //
        $contacto = Contacto::findOrFail($id);


        // Paginamos los contactos con sus pivots
        $avaluos = $contacto->avaluos()
        ->withPivot(['fecha_asignacion', 'observaciones'])
        ->orderBy('avaluos.created_at', 'desc') // o 'asc'
        ->paginate(5);
        //dd($avaluos);
        return Inertia::render('Contactos/Show', [
            'contacto' => $contacto,
            'avaluos' => $avaluos,
        ]);
    }

    public function create()
    {
        $list_genero = $this->dropdownService->list_genero();
        return Inertia::render('Contactos/Create', [
            'genero' => $list_genero,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'genero' => 'required|string|max:255',
            'celular' => 'required|string|max:255',
        ]);

        // Crear el contacto sin el logo primero para obtener el ID
        $contacto = Contacto::create($validatedData);



        return redirect()->route('contactos.index')->with('success', 'Contacto creado correctamente.');
    }

    public function edit($id)
    {
        $contacto = Contacto::findOrFail($id);
        $list_genero = $this->dropdownService->list_genero();
        return Inertia::render('Contactos/Edit', [
            'contacto' => $contacto,
            'genero' => $list_genero,
        ]);
    }

    public function update(Request $request, $id)
    {
    // Encontrar el cliente
    $contacto = Contacto::findOrFail($id);

    // Validar los datos del formulario
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:255',
        'genero' => 'required|string|max:255',
        'celular' => 'required|string|max:255',
    ], [
        'nombre.required' => 'El nombre es requerido.',
        'genero.required' => 'El genero es requerido.',
        'celular.required' => 'El celular es requerido.',
    ]);

    // Actualizar los campos del cliente (excepto el logo)
    $contacto->update($validatedData);

    // Redireccionar con un mensaje de éxito
    return redirect()->route('contactos.index')->with('success', 'Contacto actualizado correctamente.');
    }
}
