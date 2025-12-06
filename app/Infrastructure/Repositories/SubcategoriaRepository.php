<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Subcategoria;
use App\Domain\Interfaces\SubcategoriaRepositoryInterface;
use App\Infrastructure\Models\SubcategoriaModel;

class SubcategoriaRepository extends BaseRepository implements SubcategoriaRepositoryInterface
{
    public function __construct(SubcategoriaModel $model)
    {
        parent::__construct($model);
    }

    public function findByCategoria(int $idCategoria): array
    {
        $models = $this->model->where('id_categoria', $idCategoria)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function find($id): ?Subcategoria
    {
        $model = parent::find($id);
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function all(array $columns = ['*']): array
    {
        $models = $this->model->all($columns);
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function create(array $data): Subcategoria
    {
        $model = parent::create($data);
        return $this->toEntity($model);
    }

    private function toEntity(SubcategoriaModel $model): Subcategoria
    {
        $entity = new Subcategoria(
            $model->nombre,
            $model->id_categoria
        );
        
        if ($model->id_subcategoria) {
            $entity->setId($model->id_subcategoria);
        }

        return $entity;
    }
}

