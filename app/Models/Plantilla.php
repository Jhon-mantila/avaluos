<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Plantilla extends Model
{
    /** @use HasFactory<\Database\Factories\PlantillaFactory> */
    use HasFactory;
    use HasUuids; // para visualizar bien los id creados con uuid

    protected $fillable = [
        'id',
        'nombre_plantilla',
        'informacion_visita_id',
        'user_id',
    ];

    public function informacionVisita(){
        return $this->belongsTo(InformacionVisita::class, 'informacion_visita_id');
    }
}
