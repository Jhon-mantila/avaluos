<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use Illuminate\Http\Request;

class PlantillaController extends Controller
{
    public function getImages($id)
    {
        $plantilla = Plantilla::with('registrosFotograficos')->findOrFail($id);
        return response()->json($plantilla->registrosFotograficos);
    }
}
