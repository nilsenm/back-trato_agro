<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVentaModel extends Model
{
    protected $table = 'detalle_venta';
    protected $primaryKey = 'id_detalle_venta';
    public $timestamps = true;

    protected $fillable = [
        'cantidad',
        'id_stock',
        'id_venta',
    ];
}

