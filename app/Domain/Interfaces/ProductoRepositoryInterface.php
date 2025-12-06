<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Producto;

interface ProductoRepositoryInterface extends RepositoryInterface
{
    public function findByNombre(string $nombre): ?Producto;
    
    public function findBySubcategoria(int $idSubcategoria): array;
}









