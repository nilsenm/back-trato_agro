<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class MensajeModel extends Model
{
    protected $table = 'mensajes';
    protected $primaryKey = 'id_mensaje';
    public $timestamps = true;

    protected $fillable = [
        'id_oferta',
        'id_usuario_remitente',
        'id_usuario_destinatario',
        'mensaje',
        'leido',
        'fecha_leido',
    ];

    protected $casts = [
        'leido' => 'boolean',
        'fecha_leido' => 'datetime',
    ];
}

