<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Persona;
use App\Domain\Interfaces\PersonaRepositoryInterface;
use App\Infrastructure\Models\PersonaModel;

class PersonaRepository extends BaseRepository implements PersonaRepositoryInterface
{
    public function __construct(PersonaModel $model)
    {
        parent::__construct($model);
    }

    public function findByDocumento(string $numeroDocumento, string $tipoDocumento): ?Persona
    {
        $model = $this->model
            ->where('numero_documento', $numeroDocumento)
            ->where('tipo_documento', $tipoDocumento)
            ->first();
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function find($id): ?Persona
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

    public function create(array $data): Persona
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

    private function toEntity(PersonaModel $model): Persona
    {
        return new Persona(
            $model->numero_documento,
            $model->tipo_documento,
            $model->nombres,
            $model->apellido_paterno,
            $model->apellido_materno,
            $model->nombre_completo,
            $model->razon_social,
            $model->direccion,
            $model->ubigeo,
            $model->distrito,
            $model->provincia,
            $model->departamento,
            $model->estado,
            $model->condicion,
            $model->es_agente_retencion ?? false,
            $model->es_buen_contribuyente ?? false,
            $model->digito_verificador,
            $model->datos_completos
        );
    }
}

