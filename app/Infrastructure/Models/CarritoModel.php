<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoModel extends Model
{
    protected $table = 'carrito';
    protected $primaryKey = 'id_carrito';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'id_stock',
        'cantidad',
    ];

    protected $casts = [
        'id_usuario' => 'integer',
        'id_stock' => 'integer',
        'cantidad' => 'integer',
    ];
}

