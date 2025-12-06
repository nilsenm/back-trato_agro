<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoModel extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'id_subcategoria',
        'id_usuario',
        'estado',
    ];
}




