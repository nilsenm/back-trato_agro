<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class OfertaModel extends Model
{
    protected $table = 'ofertas';
    protected $primaryKey = 'id_oferta';
    public $timestamps = true;

    protected $fillable = [
        'id_stock',
        'id_usuario_ofertante',
        'id_usuario_vendedor',
        'precio_ofertado',
        'cantidad',
        'tipo_moneda',
        'estado',
        'mensaje',
        'fecha_respuesta',
    ];

    protected $casts = [
        'precio_ofertado' => 'decimal:2',
        'cantidad' => 'integer',
        'fecha_respuesta' => 'datetime',
    ];
}

