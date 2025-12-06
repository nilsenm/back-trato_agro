<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\PersonaJuridica;
use App\Domain\Interfaces\PersonaJuridicaRepositoryInterface;
use App\Infrastructure\Models\PersonaJuridicaModel;

class PersonaJuridicaRepository extends BaseRepository implements PersonaJuridicaRepositoryInterface
{
    public function __construct(PersonaJuridicaModel $model)
    {
        parent::__construct($model);
    }

    public function findByRuc(string $ruc): ?PersonaJuridica
    {
        $model = $this->model->find($ruc);
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function find($id): ?PersonaJuridica
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

    public function create(array $data): PersonaJuridica
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

    private function toEntity(PersonaJuridicaModel $model): PersonaJuridica
    {
        $entity = new PersonaJuridica(
            $model->ruc,
            $model->razon_social,
            $model->domicilio_fiscal,
            $model->nombre_representante_legal,
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

