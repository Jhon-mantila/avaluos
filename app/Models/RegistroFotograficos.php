<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RegistroFotograficos extends Model
{
    /** @use HasFactory<\Database\Factories\RegistroFotograficosFactory> */
    use HasFactory;
    use HasUuids; // para visualizar bien los id creados con uuid

    protected $fillable = [
        'id',
        'informacion_visita_id',
        'foto',
        'descripcion',
        'tipo_foto',
        'orden',

    ];

    protected function informacion_visita(){
        return $this->belongsTo(InformacionVisita::class);
    }
}
