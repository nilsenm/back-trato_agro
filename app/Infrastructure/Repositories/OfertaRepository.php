<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Oferta;
use App\Domain\Interfaces\OfertaRepositoryInterface;
use App\Infrastructure\Models\OfertaModel;

class OfertaRepository extends BaseRepository implements OfertaRepositoryInterface
{
    public function __construct(OfertaModel $model)
    {
        parent::__construct($model);
    }

    public function findByStock(int $idStock): array
    {
        $models = $this->model->where('id_stock', $idStock)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findByUsuarioOfertante(int $idUsuario): array
    {
        $models = $this->model->where('id_usuario_ofertante', $idUsuario)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findByUsuarioVendedor(int $idUsuario): array
    {
        $models = $this->model->where('id_usuario_vendedor', $idUsuario)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findPendientesByStock(int $idStock): array
    {
        $models = $this->model->where('id_stock', $idStock)
            ->where('estado', 'PENDIENTE')
            ->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function aceptar(int $idOferta): bool
    {
        $model = $this->model->find($idOferta);
        
        if (!$model) {
            return false;
        }

        return $model->update([
            'estado' => 'ACEPTADA',
            'fecha_respuesta' => now(),
        ]);
    }

    public function rechazar(int $idOferta): bool
    {
        $model = $this->model->find($idOferta);
        
        if (!$model) {
            return false;
        }

        return $model->update([
            'estado' => 'RECHAZADA',
            'fecha_respuesta' => now(),
        ]);
    }

    public function cancelar(int $idOferta): bool
    {
        $model = $this->model->find($idOferta);
        
        if (!$model) {
            return false;
        }

        return $model->update([
            'estado' => 'CANCELADA',
            'fecha_respuesta' => now(),
        ]);
    }

    public function find($id): ?Oferta
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

    public function create(array $data): Oferta
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

    public function delete($id): bool
    {
        $model = $this->model->find($id);
        
        if (!$model) {
            return false;
        }

        return $model->delete();
    }

    private function toEntity(OfertaModel $model): Oferta
    {
        $entity = new Oferta(
            $model->id_stock,
            $model->id_usuario_ofertante,
            $model->id_usuario_vendedor,
            (float) $model->precio_ofertado,
            $model->cantidad,
            $model->tipo_moneda ?? 'PEN',
            $model->estado ?? 'PENDIENTE',
            $model->mensaje,
            $model->fecha_respuesta ? $model->fecha_respuesta->toDateTimeString() : null
        );
        
        if ($model->id_oferta) {
            $entity->setId($model->id_oferta);
        }

        return $entity;
    }
}

