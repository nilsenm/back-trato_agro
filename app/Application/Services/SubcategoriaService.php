<?php

namespace App\Application\Services;

use App\Domain\Interfaces\SubcategoriaRepositoryInterface;

class SubcategoriaService extends BaseService
{
    public function __construct(SubcategoriaRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByCategoria(int $idCategoria): array
    {
        return $this->repository->findByCategoria($idCategoria);
    }
}









