<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InformacionVisita extends Model
{
    /** @use HasFactory<\Database\Factories\InformacionVisitaFactory> */
    use HasFactory;
    use HasUuids; // para visualizar bien los id creados con uuid

    protected $fillable = [
        'id',
        'avaluo_id',
        'visitador_id',
        'celular',
        'direccion',
        'ciudad',
        'fecha_visita',
        'observaciones',

    ];

    public function visitador(){
        return $this->belongsTo(Visitadores::class);
    }

    public function avaluo(){
        return $this->belongsTo(Avaluos::class, 'avaluo_id');
    }
    
    public function plantillas(){
        return $this->hasMany(Plantilla::class, 'informacion_visita_id');
    }
}
