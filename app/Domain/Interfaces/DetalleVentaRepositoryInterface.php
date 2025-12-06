<?php

namespace App\Domain\Interfaces;

interface DetalleVentaRepositoryInterface extends RepositoryInterface
{
    public function findByVenta(int $idVenta): array;
    public function findByStock(int $idStock): array;
    public function getCantidadVendida(int $idStock): int;
}








