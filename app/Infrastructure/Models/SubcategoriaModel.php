<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class SubcategoriaModel extends Model
{
    protected $table = 'subcategoria';
    protected $primaryKey = 'id_subcategoria';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'id_categoria',
    ];
}




