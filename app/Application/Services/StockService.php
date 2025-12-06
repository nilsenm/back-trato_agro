<?php

namespace App\Application\Services;

use App\Domain\Entities\Stock;
use App\Domain\Interfaces\StockRepositoryInterface;

class StockService extends BaseService
{
    public function __construct(StockRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByUsuario(int $idUsuario): array
    {
        return $this->repository->findByUsuario($idUsuario);
    }

    public function findByProducto(int $idProducto): array
    {
        return $this->repository->findByProducto($idProducto);
    }

    public function findConStock(): array
    {
        return $this->repository->findConStock();
    }

    public function findDestacados(): array
    {
        return $this->repository->findDestacados();
    }
}

