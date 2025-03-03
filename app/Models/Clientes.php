<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Clientes extends Model
{
    /** @use HasFactory<\Database\Factories\ClientesFactory> */
    use HasFactory;
    use HasUuids; // para visualizar bien los id creados con uuid

    protected $fillable = [
        'id',
        'nombre',
        'tipo_documento',
        'documento',
        'email',
        'logo',
        'telefono',
        'direccion',
        'ciudad',
    ];

    public function avaluo(){
        return $this->hasMany(Avaluos::class, 'cliente_id');
    }
    


}
