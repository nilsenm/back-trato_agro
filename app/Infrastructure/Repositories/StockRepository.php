<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Stock;
use App\Domain\Interfaces\StockRepositoryInterface;
use App\Infrastructure\Models\StockModel;

class StockRepository extends BaseRepository implements StockRepositoryInterface
{
    public function __construct(StockModel $model)
    {
        parent::__construct($model);
    }

    public function findByUsuario(int $idUsuario): array
    {
        $models = $this->model->where('id_usuario', $idUsuario)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findByProducto(int $idProducto): array
    {
        $models = $this->model->where('id_producto', $idProducto)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findConStock(): array
    {
        $models = $this->model->where('cantidad', '>', 0)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function find($id): ?Stock
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

    public function create(array $data): Stock
    {
        $model = parent::create($data);
        return $this->toEntity($model);
    }

    private function toEntity(StockModel $model): Stock
    {
        $entity = new Stock(
            $model->cantidad,
            $model->precio ? (float) $model->precio : null,
            $model->imagen,
            $model->id_usuario,
            $model->id_producto,
            $model->id_unidad,
            $model->tipo_moneda ?? 'PEN',
            $model->recibe_ofertas ?? false
        );
        
        if ($model->id_stock) {
            $entity->setId($model->id_stock);
        }

        return $entity;
    }
}


