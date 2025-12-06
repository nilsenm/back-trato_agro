<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\PersonaNatural;
use App\Domain\Interfaces\PersonaNaturalRepositoryInterface;
use App\Infrastructure\Models\PersonaNaturalModel;

class PersonaNaturalRepository extends BaseRepository implements PersonaNaturalRepositoryInterface
{
    public function __construct(PersonaNaturalModel $model)
    {
        parent::__construct($model);
    }

    public function findByDni(string $dni): ?PersonaNatural
    {
        $model = $this->model->find($dni);
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function find($id): ?PersonaNatural
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

    public function create(array $data): PersonaNatural
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

    private function toEntity(PersonaNaturalModel $model): PersonaNatural
    {
        $entity = new PersonaNatural(
            $model->dni,
            $model->nombres,
            $model->apellidos,
            $model->direccion,
            $model->celular,
            $model->pais,
            $model->departamento,
            $model->provincia,
            $model->distrito,
            $model->id_usuario
        );
        
        return $entity;
    }
}

