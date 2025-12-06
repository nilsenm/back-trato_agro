<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Categoria;

interface CategoriaRepositoryInterface extends RepositoryInterface
{
    public function findByNombre(string $nombre): ?Categoria;
}









