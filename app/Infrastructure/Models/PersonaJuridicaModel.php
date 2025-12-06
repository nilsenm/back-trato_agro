<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaJuridicaModel extends Model
{
    protected $table = 'persona_juridica';
    protected $primaryKey = 'ruc';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'ruc',
        'razon_social',
        'domicilio_fiscal',
        'nombre_representante_legal',
        'celular',
        'pais',
        'departamento',
        'provincia',
        'distrito',
        'id_usuario',
    ];
}


