<?php

namespace App\Http\Controllers;
use App\Models\Avaluos;
use App\Models\Clientes;
use App\Models\Plantilla;
use App\Models\Contacto;
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
        $avaluo = Avaluos::with([
            'cliente', 
            'departamento', 
            'municipio',
            'informacionVisitas.visitador.user', 
            'informacionVisitas.plantillas',
            ])->findOrFail($id);
        //dd($avaluo);
        
        // Paginamos los contactos con sus pivots
        $contactos = $avaluo->contactos()
        ->withPivot(['fecha_asignacion', 'observaciones'])
        ->orderBy('contactos.created_at', 'desc') // o 'asc'
        ->paginate(5);

        $informacionVisitas = $avaluo->informacionVisitas()->with('visitador.user')->paginate(5);

        //$informacionPlantillas = $avaluo->informacionVisitas()->pluck('plantillas')->flatten();
        $informacionPlantillas = Plantilla::whereHas('informacionVisita', function ($query) use ($id) {
            $query->where('avaluo_id', $id);
        })->with('informacionVisita')->paginate(5);  
        
        $contactosDisponibles = Contacto::whereNotIn('id', $avaluo->contactos->pluck('id'))->get();
        //dd($contactosDisponibles);
        return Inertia::render('Avaluos/Show', [
            'avaluo' => $avaluo,
            'contactos' => $contactos, // <--- Ahora sí enviamos contactos
            'informacionVisitas' => $informacionVisitas,
            'informacionPlantillas' => $informacionPlantillas,
            'generos' => $this->dropdownService->list_genero(),
            'contactosDisponibles' => $contactosDisponibles,
        ]);
    }

    public function create()
    {
        $list_estados = $this->dropdownService->list_estados();
        $list_tipos_avaluos = $this->dropdownService->list_tipos_avaluos();
        $list_uso = $this->dropdownService->list_uso();

        $clientes = Clientes::all();
        return Inertia::render('Avaluos/Create', [
            'clientes' => $clientes,
            'estados' => $list_estados,
            'tiposAvaluo' => $list_tipos_avaluos,
            'tiposUso' => $list_uso,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numero_avaluo' => 'required|string|max:255|unique:avaluos,numero_avaluo',
            'tipo_avaluo' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'municipio_id' => 'nullable|string|max:255',
            'departamento_id' => 'nullable|string|max:255',
            'uso' => 'nullable|string|max:255',
            'valor_comercial_estimado' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
            'cliente_id' => 'required|exists:clientes,id',
            'estado' => 'required|string|max:255',
            'auxiliar' => 'nullable|string|max:255',
            'fecha_entrega_avaluo' => 'nullable|date',
            'valor_informe' => 'nullable|numeric',
        ], [
            'numero_avaluo.unique' => 'El número de avalúo ya existe. Por favor, elija un número diferente.',
            'cliente_id.required' => 'El cliente es requerido.',
            'direccion.required' => 'La dirección es requeida.',
            'numero_avaluo.required' => 'El número de avalúo es requerido.',
            'tipo_avaluo.required' => 'El tipo de avalúo es requerido.',
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
        $list_uso = $this->dropdownService->list_uso();
        $clientes = Clientes::all();

        return Inertia::render('Avaluos/Edit', [
            'avaluo' => $avaluo,
            'clientes' => $clientes,
            'estados' => $list_estados,
            'tiposAvaluo' => $list_tipos_avaluos,
            'tiposUso' => $list_uso,
        ]);
    }

    public function update(Request $request, $id)
    {
        $avaluo = Avaluos::findOrFail($id);

        $validatedData = $request->validate([
            'numero_avaluo' => 'required|string|max:255|unique:avaluos,numero_avaluo,' . $id,
            'tipo_avaluo' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'municipio_id' => 'nullable|string|max:255',
            'departamento_id' => 'nullable|string|max:255',
            'uso' => 'nullable|string|max:255',
            'valor_comercial_estimado' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
            'cliente_id' => 'required|exists:clientes,id',
            'estado' => 'required|string|max:255',
            'auxiliar' => 'nullable|string|max:255',
            'fecha_entrega_avaluo' => 'nullable|date',
            'valor_informe' => 'nullable|numeric',
        ], [
            'numero_avaluo.unique' => 'El número de avalúo ya existe. Por favor, elija un número diferente.',
            'cliente_id.required' => 'El cliente es requerido.',
            'direccion.required' => 'La dirección es requeida.',
            'numero_avaluo.required' => 'El número de avalúo es requerido.',
            'tipo_avaluo.required' => 'El tipo de avalúo es requerido.',
            'estado.required' => 'El estado es requerido.',
        ]);

        $avaluo->update($validatedData);

        return redirect()->route('avaluos.index')->with('success', 'Avalúo actualizado correctamente.');
    }

    public function updateCampo(Request $request, $id)
    {
        $avaluo = Avaluos::findOrFail($id);

        $campo = $request->keys()[0]; // e.g. 'valor_informe'
        $valor = $request->input($campo);

        $rules = [
            'auxiliar' => 'nullable|string|max:255',
            'fecha_entrega_avaluo' => 'nullable|date',
            'valor_informe' => 'nullable|numeric',
        ];
    
        // Validar solo ese campo
        $validated = $request->validate([
            $campo => $rules[$campo] ?? 'nullable',
        ]);

        $avaluo->$campo = $valor;
        $avaluo->save();
    
        return back()->with('success', 'Campo actualizado correctamente.');
    }
}
