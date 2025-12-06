<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Subcategoria;

interface SubcategoriaRepositoryInterface extends RepositoryInterface
{
    public function findByCategoria(int $idCategoria): array;
}









