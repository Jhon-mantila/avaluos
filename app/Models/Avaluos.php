<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Avaluos extends Model
{
    /** @use HasFactory<\Database\Factories\AvaluosFactory> */
    use HasFactory;
    use HasUuids; // para visualizar bien los id creados con uuid

    protected $fillable = [
        'id',
        'numero_avaluo',
        'estado',
        'tipo_avaluo',
        'direccion',
        'ciudad',
        'departamento',
        'uso',
        'valor_comercial_estimado',
        'observaciones',
        'cliente_id',
    ];

    public function cliente(){
        return $this->belongsTo(Clientes::class);
    }

    public function informacionVisitas(){
        return $this->hasMany(InformacionVisita::class, 'avaluo_id');
    }

}
