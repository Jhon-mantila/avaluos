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

                // 🔹 Obtener solo el nombre del archivo SIN extensión
                $nombreSinExtension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                if (preg_match('/^(\d+)\.\s*(.*)$/', $nombreSinExtension, $match)) {
                    $numero = $match[1];
                    $nombre = $match[2]; // El resto es el nombre limpio
                }
                else {
                    $numero = $ultimoOrden + $index + 1; // Sumar al último orden
                    $nombre = $nombreSinExtension; // Usar el nombre original
                }

                Log::info("📌 NUMERO IMAGEN: {$numero}");
                // Guardar en la base de datos
                $imagen = RegistroFotografico::create([
                    'id' => Str::uuid(),
                    'plantilla_id' => $request->plantilla_id,
                    'imagen' => $path, // Guardar la ruta en la BD
                    'title' => $nombre,
                    'tipo' => $file->getClientMimeType(),
                    'orden' => $numero, // Sumar al último orden
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
            RegistroFotografico::where('id', $order['id'])->update(['orden' => $order['order']]);
        }

        return response()->json(['message' => 'Orden actualizado correctamente']);
    }

    public function update(Request $request, $id)
    {
        $imagen = RegistroFotografico::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Si se subió una nueva imagen
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior
            //Storage::delete("public/{$imagen->imagen}");

            $rutaImagen = public_path("storage/{$imagen->imagen}");

            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
                //Log::info("🗑️ Imagen eliminada con unlink: {$rutaImagen}");
            } else {
                Log::warning("⚠️ Archivo no encontrado en public_path: {$rutaImagen}");
            }

            // Guardar la nueva imagen
            $folder = 'images/' . $imagen->plantilla_id;
            $path = $request->file('image')->store($folder, 'public');

            // Actualizar la base de datos con el nuevo nombre sin extensión
            $nombreSinExtension = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);

            $imagen->update([
                'imagen' => $path,
                'title' => $nombreSinExtension,
                //'tipo' => $request->file('image')->getClientOriginalName(),
            ]);
        } else {
            // Solo actualizar el título si no hay nueva imagen
            $imagen->update(['title' => $validatedData['title']]);
        }

        return response()->json(['message' => 'Imagen actualizada con éxito']);
    }

    public function destroy($id)
    {
        $registro = RegistroFotografico::findOrFail($id);

        // Si no se encuentra, registrar el error
        if (!$registro) {
            Log::error("❌ Error: No se encontró el registro con ID: {$id}");
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        // Log para verificar qué ruta se está almacenando en la base de datos
        Log::info("📌 Imagen almacenada en la BD: {$registro->imagen}");
        
        $rutaImagen = public_path("storage/{$registro->imagen}");

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
            //Log::info("🗑️ Imagen eliminada con unlink: {$rutaImagen}");
        } else {
            Log::warning("⚠️ Archivo no encontrado en public_path: {$rutaImagen}");
        }
    
    
        // Eliminar el registro de la base de datos
        $registro->delete();
    
        return response()->json(['message' => 'Imagen eliminada correctamente']);
    }

    public function destroyMultiple(Request $request)
    {
        \Log::info("🛠️ DELETE múltiple recibido", ['payload' => $request->all()]);

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:registros_fotograficos,id',
        ]);

        $ids = $request->input('ids');
        $imagenesEliminadas = [];

        foreach ($ids as $id) {
            $registro = RegistroFotografico::findOrFail($id);

            // Log para verificar qué ruta se está almacenando en la base de datos
            Log::info("📌 Imagen almacenada en la BD: {$registro->imagen}");
            
            $rutaImagen = public_path("storage/{$registro->imagen}");

            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
                Log::info("🗑️ Imagen eliminada con unlink: {$rutaImagen}");
            } else {
                Log::warning("⚠️ Archivo no encontrado en public_path: {$rutaImagen}");
            }

            // Eliminar el registro de la base de datos
            $registro->delete();
            $imagenesEliminadas[] = $registro->id;
        }

        return response()->json(['message' => 'Imágenes eliminadas correctamente', 'deleted_ids' => $imagenesEliminadas]);
    }
}
