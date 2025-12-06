<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Usuario;

interface UsuarioRepositoryInterface extends RepositoryInterface
{
    public function findByDocumento(string $documento): ?Usuario;
    
    public function findByCorreo(string $correo): ?Usuario;
    
    public function findByUsuario(string $usuario): ?Usuario;
}


