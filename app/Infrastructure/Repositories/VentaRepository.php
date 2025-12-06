<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Venta;
use App\Domain\Interfaces\VentaRepositoryInterface;
use App\Infrastructure\Models\VentaModel;

class VentaRepository extends BaseRepository implements VentaRepositoryInterface
{
    public function __construct(VentaModel $model)
    {
        parent::__construct($model);
    }

    public function findByUsuarioCompra(int $idUsuario): array
    {
        $models = $this->model->where('id_usuario_compra', $idUsuario)
            ->orderBy('id_venta', 'desc')
            ->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function getUltimaVenta(int $idUsuario): ?Venta
    {
        $model = $this->model->where('id_usuario_compra', $idUsuario)
            ->orderBy('id_venta', 'desc')
            ->first();
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function find($id): ?Venta
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

    public function create(array $data): Venta
    {
        $model = parent::create($data);
        return $this->toEntity($model);
    }

    private function toEntity(VentaModel $model): Venta
    {
        $fecha = $model->fecha ? new \DateTime($model->fecha) : null;
        $hora = $model->hora ? new \DateTime($model->hora) : null;
        
        $entity = new Venta(
            $model->id_distrito,
            $fecha,
            $hora,
            $model->id_usuario_compra
        );
        
        if ($model->id_venta) {
            $entity->setId($model->id_venta);
        }

        return $entity;
    }
}

