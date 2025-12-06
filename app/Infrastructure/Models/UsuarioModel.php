<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UsuarioModel extends Authenticatable implements JWTSubject
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'documento',
        'username',
        'nombre',
        'correo',
        'clave',
        'estado',
        'tipo_vendedor',
        'tipo_persona',
    ];

    protected $hidden = [
        'clave',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'documento' => $this->documento,
            'correo' => $this->correo,
            'tipo_vendedor' => $this->tipo_vendedor,
            'tipo_persona' => $this->tipo_persona,
        ];
    }
}

