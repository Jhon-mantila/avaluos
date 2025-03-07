<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use Illuminate\Http\Request;
use App\Models\RegistroFotografico;

class PlantillaController extends Controller
{
    public function getImages($id)
    {
        try {
            $imagenes = RegistroFotografico::where('plantilla_id', $id)
                ->orderBy('orden', 'asc')
                ->get();
    
            return response()->json($imagenes);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
