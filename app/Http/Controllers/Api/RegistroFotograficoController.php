<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistroFotografico;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegistroFotograficoController extends Controller
{
    public function store(Request $request)
    {
        // Validar la solicitud
        $validatedData = $request->validate([
            'plantilla_id' => 'required|string|exists:plantillas,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagenesGuardadas = [];

        if ($request->hasFile('images')) {

            // Obtener el último número de orden registrado para esta plantilla
            $ultimoOrden = RegistroFotografico::where('plantilla_id', $request->plantilla_id)
            ->max('orden') ?? 0; // Si no hay registros, comienza en 0

            foreach ($request->file('images') as $index => $file) {
                // Crear la carpeta con el ID de la plantilla
                $folder = 'images/' . $request->plantilla_id;
                
                // Guardar el archivo en `storage/app/public/images/{plantilla_id}/`
                $path = $file->store($folder, 'public');

                // Guardar en la base de datos
                $imagen = RegistroFotografico::create([
                    'id' => Str::uuid(),
                    'plantilla_id' => $request->plantilla_id,
                    'imagen' => $path, // Guardar la ruta en la BD
                    'title' => $file->getClientOriginalName(),
                    'tipo' => $file->getClientMimeType(),
                    'orden' => $ultimoOrden + $index + 1, // Sumar al último orden
                ]);

                $imagenesGuardadas[] = $imagen;
            }
        }

        return response()->json([
            'message' => 'Imágenes subidas con éxito',
            'images' => $imagenesGuardadas
        ], 201);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:registros_fotograficos,id',
            'orders.*.order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            RegistroFotografico::where('id', $order['id'])->update(['order' => $order['order']]);
        }

        return response()->json(['message' => 'Orden actualizado correctamente']);
    }

    public function destroy($id)
    {
        $registro = RegistroFotografico::findOrFail($id);
        Storage::delete(str_replace('/storage', 'public', $registro->url));
        $registro->delete();

        return response()->json(['message' => 'Imagen eliminada correctamente']);
    }
}
