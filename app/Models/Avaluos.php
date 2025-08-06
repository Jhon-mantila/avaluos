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
        'municipio_id', // antes 'ciudad'
        'departamento_id', // antes 'departamento'
        'uso',
        'valor_comercial_estimado',
        'observaciones',
        'cliente_id',
        'auxiliar',
        'fecha_entrega_avaluo',
        'valor_informe',
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class);
    }

    public function informacionVisitas()
    {
        return $this->hasMany(InformacionVisita::class, 'avaluo_id');
    }

    public function contactos()
    {
        return $this->belongsToMany(Contacto::class, 'avaluo_contacto', 'avaluo_id', 'contacto_id')
            ->withPivot(['fecha_asignacion', 'observaciones'])
            ->withTimestamps();
    }
    public function departamento()
    {
        return $this->belongsTo(Departamento::class , 'departamento_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class , 'municipio_id');
    }

}
