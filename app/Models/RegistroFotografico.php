<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RegistroFotografico extends Model
{
    /** @use HasFactory<\Database\Factories\RegistroFotograficoFactory> */
    use HasFactory;
    use HasUuids; // para visualizar bien los id creados con uuid
    protected $table = 'registros_fotograficos';

    protected $fillable = [
        'id',
        'plantilla_id',
        'imagen',
        'title',
        'tipo',
        'orden',
        'pagina',
        'posicion',
        'user_id',
    ];

    public function plantilla(){
        return $this->hasMany(Plantilla::class, 'plantilla_id');
    }
}
