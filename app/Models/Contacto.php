<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Contacto extends Model
{
    //
    use HasFactory;
    use HasUuids; // para visualizar bien los id creados con uuid

    protected $fillable = [
        'id',
        'nombre',
        'genero',
        'celular',
    ];

    public function avaluos()
    {
        return $this->belongsToMany(Avaluo::class, 'avaluo_contacto', 'contacto_id', 'avaluo_id')
            ->withPivot(['fecha_asignacion', 'observaciones'])
            ->withTimestamps();
    }
}
