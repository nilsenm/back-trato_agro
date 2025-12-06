<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class VentaModel extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id_venta';
    public $timestamps = true;

    protected $fillable = [
        'fecha',
        'hora',
        'id_usuario_compra',
        'id_distrito',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime',
    ];
}




