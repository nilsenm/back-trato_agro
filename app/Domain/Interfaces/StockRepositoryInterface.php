<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Stock;

interface StockRepositoryInterface extends RepositoryInterface
{
    public function findByUsuario(int $idUsuario): array;
    
    public function findByProducto(int $idProducto): array;
    
    public function findConStock(): array;
}









