<?php

namespace App\Application\Services;

use App\Domain\Entities\Categoria;
use App\Domain\Interfaces\CategoriaRepositoryInterface;

class CategoriaService extends BaseService
{
    public function __construct(CategoriaRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByNombre(string $nombre): ?Categoria
    {
        return $this->repository->findByNombre($nombre);
    }
}

