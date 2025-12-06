<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Carrito;

interface CarritoRepositoryInterface extends RepositoryInterface
{
    public function findByUsuario(int $idUsuario): array;
    
    public function findByUsuarioAndStock(int $idUsuario, int $idStock): ?Carrito;
    
    public function deleteByUsuario(int $idUsuario): bool;
}

