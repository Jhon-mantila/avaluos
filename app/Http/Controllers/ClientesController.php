<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Clientes;
use Inertia\Inertia;
use App\Services\DropdownService;

class ClientesController extends Controller
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
        $list_tipo_documento = $this->dropdownService->list_tipo_documento();
        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente,
            'tipo_documento' => $list_tipo_documento,
        ]);
    }

    public function update(Request $request, $id)
    {
    // Encontrar el cliente
    $cliente = Clientes::findOrFail($id);

    // Validar los datos del formulario
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:255',
        'tipo_documento' => 'required|string|max:255',
        'documento' => 'required|string|max:255|unique:clientes,documento,' . $id,
        'email' => 'required|email|max:255',
        'telefono' => 'required|string|max:255',
        'ciudad' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'nombre.required' => 'El nombre es requerido.',
        'tipo_documento.required' => 'El tipo de documento es requerido.',
        'documento.required' => 'El documento es requerido.',
        'documento.unique' => 'El número de documento ya existe. Por favor, elija un número diferente.',
        'email.required' => 'El email es requerido.',
        'telefono.required' => 'El teléfono es requerido',
    ]);

    // Actualizar los campos del cliente (excepto el logo)
    $cliente->update($validatedData);

    // Manejar el archivo de logo
    if ($request->hasFile('logo')) {
        // Eliminar el logo anterior si existe
        if ($cliente->logo) {
            Storage::delete('public/' . $cliente->logo);
        }

        // Crear una carpeta con el ID del cliente
        $folder = 'logos/' . $cliente->id;
        // Guardar el archivo en la carpeta creada
        $path = $request->file('logo')->store($folder, 'public');
        // Actualizar el cliente con la ruta del logo
        $cliente->update(['logo' => $path]);
    }

    // Redireccionar con un mensaje de éxito
    return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function create()
    {
        $list_tipo_documento = $this->dropdownService->list_tipo_documento();
        return Inertia::render('Clientes/Create', [
            'tipo_documento' => $list_tipo_documento,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        $list_tipo_documento = $this->dropdownService->list_tipo_documento();

        return Inertia::render('Clientes/Show', [
            'cliente' => $cliente,
            'avaluos' => $avaluos,
            'tipo_documento' => $list_tipo_documento,
        ]);
    }
}
