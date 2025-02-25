<?php

namespace App\Http\Controllers;
use App\Models\Avaluos;
use App\Models\Clientes;
use Inertia\Inertia;
use App\Services\DropdownService;
use Illuminate\Http\Request;

class AvaluosController extends Controller
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
        $query = Avaluos::with('cliente'); // Incluir la relación con el cliente

        // Aplicar búsqueda si hay un término
        if ($search) {
            $query->where('numero_avaluo', 'LIKE', "%{$search}%")
                  ->orWhere('estado', 'LIKE', "%{$search}%")
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

        public function show($id)
    {
        $avaluo = Avaluos::with(['cliente', 'informacionVisitas.visitador.user'])->findOrFail($id);

        $informacionVisitas = $avaluo->informacionVisitas()->with('visitador.user')->paginate(5);
        
        //dd($informacionVisitas);
        return Inertia::render('Avaluos/Show', [
            'avaluo' => $avaluo,
            'informacionVisitas' => $informacionVisitas,
        ]);
    }

    public function create()
    {
        $list_estados = $this->dropdownService->list_estados();
        $list_tipos_avaluos = $this->dropdownService->list_tipos_avaluos();

        $clientes = Clientes::all();
        return Inertia::render('Avaluos/Create', [
            'clientes' => $clientes,
            'estados' => $list_estados,
            'tiposAvaluo' => $list_tipos_avaluos,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numero_avaluo' => 'required|string|max:255|unique:avaluos,numero_avaluo',
            'tipo_avaluo' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'area' => 'nullable|numeric',
            'valor_comercial_estimado' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
            'cliente_id' => 'required|exists:clientes,id',
            'estado' => 'required|string|max:255',
        ], [
            'numero_avaluo.unique' => 'El número de avalúo ya existe. Por favor, elija un número diferente.',
            'cliente_id.required' => 'El cliente es requerido.',
            'direccion.required' => 'La dirección es requeida.',
            'numero_avaluo.required' => 'El número de avalúo es requerido.',
            'tipo_avaluo.required' => 'El tipo de avalúo es requerido.',
            'area.numeric' => 'El área es númerico.',
            'estado.required' => 'El estado es requerido.',
        ]);

        Avaluos::create($validatedData);

        return redirect()->route('avaluos.index')->with('success', 'Avalúo creado correctamente.');
    }

    public function edit($id)
    {
        $avaluo = Avaluos::findOrFail($id);
        $list_estados = $this->dropdownService->list_estados();
        $list_tipos_avaluos = $this->dropdownService->list_tipos_avaluos();
        $clientes = Clientes::all();

        return Inertia::render('Avaluos/Edit', [
            'avaluo' => $avaluo,
            'clientes' => $clientes,
            'estados' => $list_estados,
            'tiposAvaluo' => $list_tipos_avaluos,
        ]);
    }

    public function update(Request $request, $id)
    {
        $avaluo = Avaluos::findOrFail($id);

        $validatedData = $request->validate([
            'numero_avaluo' => 'required|string|max:255|unique:avaluos,numero_avaluo,' . $id,
            'tipo_avaluo' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'area' => 'nullable|numeric',
            'valor_comercial_estimado' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
            'cliente_id' => 'required|exists:clientes,id',
            'estado' => 'required|string|max:255',
        ], [
            'numero_avaluo.unique' => 'El número de avalúo ya existe. Por favor, elija un número diferente.',
            'cliente_id.required' => 'El cliente es requerido.',
            'direccion.required' => 'La dirección es requeida.',
            'numero_avaluo.required' => 'El número de avalúo es requerido.',
            'tipo_avaluo.required' => 'El tipo de avalúo es requerido.',
            'area.numeric' => 'El área es númerico.',
            'estado.required' => 'El estado es requerido.',
        ]);

        $avaluo->update($validatedData);

        return redirect()->route('avaluos.index')->with('success', 'Avalúo actualizado correctamente.');
    }
}
