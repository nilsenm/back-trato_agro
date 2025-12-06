<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaNaturalModel extends Model
{
    protected $table = 'persona_natural';
    protected $primaryKey = 'dni';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'direccion',
        'celular',
        'pais',
        'departamento',
        'provincia',
        'distrito',
        'id_usuario',
    ];
}


