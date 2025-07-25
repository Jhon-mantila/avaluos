<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvaluoContacto extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'avaluo_contacto';

    protected $fillable = [
        'id',
        'avaluo_id',
        'contacto_id',
        'fecha_asignacion',
        'observaciones',
    ];
}
