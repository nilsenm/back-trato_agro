<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Venta;

interface VentaRepositoryInterface extends RepositoryInterface
{
    public function findByUsuarioCompra(int $idUsuario): array;
    
    public function getUltimaVenta(int $idUsuario): ?Venta;
}









