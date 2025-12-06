<?php

namespace App\Application\Services;

use App\Domain\Interfaces\DetalleVentaRepositoryInterface;

class DetalleVentaService extends BaseService
{
    public function __construct(DetalleVentaRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByVenta(int $idVenta): array
    {
        return $this->repository->findByVenta($idVenta);
    }

    public function findByStock(int $idStock): array
    {
        return $this->repository->findByStock($idStock);
    }

    public function getCantidadVendida(int $idStock): int
    {
        return $this->repository->getCantidadVendida($idStock);
    }
}








