<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadModel extends Model
{
    protected $table = 'unidad';
    protected $primaryKey = 'id_unidad';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];
}




