<?php

namespace App\Http\Controllers;

use App\Models\Avaluos;
use App\Models\Contacto;
use App\Models\AvaluoContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\DropdownService;

class AvaluoContactoController extends Controller
{
    protected $dropdownService;

    public function __construct(DropdownService $dropdownService)
    {
        $this->dropdownService = $dropdownService;
    }

    public function create(Avaluos $avaluo)
    {
        // Retornar la vista de Inertia con los datos necesarios
        return Inertia::render('Avaluos/Contactos/Create', [
            'avaluo' => $avaluo,
            'generos' => $this->dropdownService->list_genero(),
        ]);
    }
    public function store(Request $request, Avaluos $avaluo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'genero' => 'nullable|string|max:50',
            'celular' => 'nullable|string|max:20',
            'fecha_asignacion' => 'nullable|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Crear el contacto
        $contacto = Contacto::create([
            'id' => (string) Str::uuid(),
            'nombre' => $validated['nombre'],
            'genero' => $validated['genero'] ?? null,
            'celular' => $validated['celular'] ?? null,
        ]);
       // Crear el registro en la tabla pivote con un modelo personalizado
        AvaluoContacto::create([
            'avaluo_id' => $avaluo->id,
            'contacto_id' => $contacto->id,
            'fecha_asignacion' => $validated['fecha_asignacion'] ?? now(),
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        //return back()->with('success', 'Contacto creado y relacionado correctamente.');

        // Retornar con el nuevo contacto ya asociado
        $nuevoContacto = $avaluo->contactos()
            ->where('contactos.id', $contacto->id)
            ->first();
        //dd($nuevoContacto);


        return back()->with('nuevoContacto', $nuevoContacto);

    }

    public function attach(Request $request, Avaluos $avaluo, Contacto $contacto)
    {
        $validated = $request->validate([
            'fecha_asignacion' => 'nullable|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Evitar duplicados
        if ($avaluo->contactos()->where('contactos.id', $contacto->id)->exists()) {
            return back()->withErrors('El contacto ya está vinculado a este avalúo.');
        }

        // Usar el modelo pivote para crear
        AvaluoContacto::create([
            'avaluo_id' => $avaluo->id,
            'contacto_id' => $contacto->id,
            'fecha_asignacion' => $validated['fecha_asignacion'] ?? now(),
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        // Traer contacto con datos pivote
        $nuevoContacto = $avaluo->contactos()
            ->where('contactos.id', $contacto->id)
            ->first();

        return back()->with('nuevoContacto', $nuevoContacto);
    }
}
