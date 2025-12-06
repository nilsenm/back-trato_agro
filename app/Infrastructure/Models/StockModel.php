<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class StockModel extends Model
{
    protected $table = 'stock';
    protected $primaryKey = 'id_stock';
    public $timestamps = true;

    protected $fillable = [
        'precio',
        'imagen',
        'id_usuario',
        'id_producto',
        'cantidad',
        'id_unidad',
        'tipo_moneda',
        'recibe_ofertas',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'cantidad' => 'integer',
        'recibe_ofertas' => 'boolean',
    ];
}




