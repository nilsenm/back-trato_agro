<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Categoria;
use App\Domain\Interfaces\CategoriaRepositoryInterface;
use App\Infrastructure\Models\CategoriaModel;

class CategoriaRepository extends BaseRepository implements CategoriaRepositoryInterface
{
    public function __construct(CategoriaModel $model)
    {
        parent::__construct($model);
    }

    public function findByNombre(string $nombre): ?Categoria
    {
        $model = $this->model->where('nombre', $nombre)->first();
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function find($id): ?Categoria
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

    public function create(array $data): Categoria
    {
        $model = parent::create($data);
        return $this->toEntity($model);
    }

    private function toEntity(CategoriaModel $model): Categoria
    {
        $entity = new Categoria(
            $model->nombre,
            $model->icono
        );
        
        if ($model->id_categoria) {
            $entity->setId($model->id_categoria);
        }

        return $entity;
    }
}


