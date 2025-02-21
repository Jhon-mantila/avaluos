<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Visitadores extends Model
{
    //
    use HasFactory;
    use HasUuids; // para visualizar bien los id creados con uuid

    protected $fillable = [
        'id',
        'cobertura',
        'ciudad',
        'departamento',
        'active',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected function informacion_visita(){
        return $this->hasMany(InformacionVisita::class);
    }
}
