<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clientes;
use Inertia\Inertia;

class ClientesController extends Controller
{
    //
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->query('search');

        // Consulta base 
        $query = Clientes::query();

        // Aplicar búsqueda si hay un término
        if ($search) {
            $query->where('nombre', 'LIKE', "%{$search}%")
            ->orWhere('ciudad', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('telefono', 'LIKE', "%{$search}%");
        }
        // Ordenar los resultados en orden descendente por la columna 'created_at'
        $query->orderBy('created_at', 'desc');

        // Paginar los resultados
        //$clientes = $query->paginate(10);
        // Paginar los resultados y conservar el parámetro de búsqueda
        $clientes = $query->paginate(10)->appends(['search' => $search]);

        // Retornar la vista de Inertia con los datos
        return Inertia::render('Clientes/Index', [
            'clientes' => $clientes,
            'filters' => $request->only(['search']),
        ]);
    }

    public function edit($id)
    {
        $cliente = Clientes::findOrFail($id);
        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente,
        ]);
    }

    public function update(Request $request, $id)
    {
        $cliente = Clientes::findOrFail($id);
        $cliente->update($request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'updated_at' => date("Y-m-d H:i:s"),
        ]));

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function create()
    {
        return Inertia::render('Clientes/Create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Crear el cliente sin el logo primero para obtener el ID
        $cliente = Clientes::create($validatedData);

        if ($request->hasFile('logo')) {
            // Crear una carpeta con el ID del cliente
            $folder = 'logos/' . $cliente->id;
            // Guardar el archivo en la carpeta creada
            $path = $request->file('logo')->store($folder, 'public');
            // Actualizar el cliente con la ruta del logo
            $cliente->update(['logo' => $path]);
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function show($id)
    {
        $cliente = Clientes::findOrFail($id);
        $avaluos = $cliente->avaluo()->paginate(5);

        return Inertia::render('Clientes/Show', [
            'cliente' => $cliente,
            'avaluos' => $avaluos,
        ]);
    }
}
