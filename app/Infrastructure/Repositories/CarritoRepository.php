<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Carrito;
use App\Domain\Interfaces\CarritoRepositoryInterface;
use App\Infrastructure\Models\CarritoModel;

class CarritoRepository extends BaseRepository implements CarritoRepositoryInterface
{
    public function __construct(CarritoModel $model)
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

    public function findByUsuarioAndStock(int $idUsuario, int $idStock): ?Carrito
    {
        $model = $this->model->where('id_usuario', $idUsuario)
            ->where('id_stock', $idStock)
            ->first();
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function deleteByUsuario(int $idUsuario): bool
    {
        return $this->model->where('id_usuario', $idUsuario)->delete();
    }

    public function find($id): ?Carrito
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

    public function create(array $data): Carrito
    {
        $model = parent::create($data);
        return $this->toEntity($model);
    }

    public function update($id, array $data): bool
    {
        $model = $this->model->find($id);
        
        if (!$model) {
            return false;
        }

        return $model->update($data);
    }

    private function toEntity(CarritoModel $model): Carrito
    {
        $entity = new Carrito(
            $model->id_usuario,
            $model->id_stock,
            $model->cantidad
        );
        
        if ($model->id_carrito) {
            $entity->setId($model->id_carrito);
        }

        return $entity;
    }
}

