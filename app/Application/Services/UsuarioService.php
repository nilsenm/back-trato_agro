<?php

namespace App\Application\Services;

use App\Domain\Entities\Usuario;
use App\Domain\Interfaces\UsuarioRepositoryInterface;

class UsuarioService extends BaseService
{
    public function __construct(UsuarioRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByDocumento(string $documento): ?Usuario
    {
        return $this->repository->findByDocumento($documento);
    }

    public function findByCorreo(string $correo): ?Usuario
    {
        return $this->repository->findByCorreo($correo);
    }
}

