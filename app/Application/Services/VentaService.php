<?php

namespace App\Application\Services;

use App\Domain\Entities\Venta;
use App\Domain\Interfaces\VentaRepositoryInterface;

class VentaService extends BaseService
{
    public function __construct(VentaRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByUsuarioCompra(int $idUsuario): array
    {
        return $this->repository->findByUsuarioCompra($idUsuario);
    }

    public function getUltimaVenta(int $idUsuario): ?Venta
    {
        return $this->repository->getUltimaVenta($idUsuario);
    }
}

