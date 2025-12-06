<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\DetalleVenta;
use App\Domain\Interfaces\DetalleVentaRepositoryInterface;
use App\Infrastructure\Models\DetalleVentaModel;

class DetalleVentaRepository extends BaseRepository implements DetalleVentaRepositoryInterface
{
    public function __construct(DetalleVentaModel $model)
    {
        parent::__construct($model);
    }

    public function findByVenta(int $idVenta): array
    {
        $models = $this->model->where('id_venta', $idVenta)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findByStock(int $idStock): array
    {
        $models = $this->model->where('id_stock', $idStock)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    /**
     * Calcula la cantidad total vendida de un stock
     */
    public function getCantidadVendida(int $idStock): int
    {
        $detalles = $this->findByStock($idStock);
        $totalVendido = 0;
        foreach ($detalles as $detalle) {
            $totalVendido += $detalle->getCantidad();
        }
        return $totalVendido;
    }

    public function find($id): ?DetalleVenta
    {
        $model = parent::find($id);
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function all(array $columns = ['*']): array
    {
        $models = parent::all($columns);
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function create(array $data): DetalleVenta
    {
        $model = parent::create($data);
        return $this->toEntity($model);
    }

    private function toEntity(DetalleVentaModel $model): DetalleVenta
    {
        $entity = new DetalleVenta(
            $model->cantidad,
            $model->id_stock,
            $model->id_venta
        );
        
        if ($model->id_detalle_venta) {
            $entity->setId($model->id_detalle_venta);
        }

        return $entity;
    }
}

