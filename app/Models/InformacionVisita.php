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

    public function visitadores(){
        return $this->belongsTo(Visitadores::class);
    }

    public function avaluos(){
        return $this->belongsTo(Avaluos::class);
    }

    protected function registro_fotograficos(){
        return $this->hasMany(RegistroFotograficos::class);
    }
}
