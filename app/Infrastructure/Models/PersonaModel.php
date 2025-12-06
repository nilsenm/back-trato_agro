<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaModel extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'numero_documento';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'numero_documento',
        'tipo_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'nombre_completo',
        'razon_social',
        'direccion',
        'ubigeo',
        'distrito',
        'provincia',
        'departamento',
        'estado',
        'condicion',
        'es_agente_retencion',
        'es_buen_contribuyente',
        'digito_verificador',
        'datos_completos',
    ];

    protected $casts = [
        'es_agente_retencion' => 'boolean',
        'es_buen_contribuyente' => 'boolean',
        'datos_completos' => 'array',
    ];
}

