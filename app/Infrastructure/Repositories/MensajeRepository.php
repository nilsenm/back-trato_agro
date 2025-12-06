<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Mensaje;
use App\Domain\Interfaces\MensajeRepositoryInterface;
use App\Infrastructure\Models\MensajeModel;

class MensajeRepository extends BaseRepository implements MensajeRepositoryInterface
{
    public function __construct(MensajeModel $model)
    {
        parent::__construct($model);
    }

    public function findByOferta(int $idOferta): array
    {
        $models = $this->model->where('id_oferta', $idOferta)
            ->orderBy('created_at', 'asc')
            ->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findByUsuarios(int $idUsuario1, int $idUsuario2): array
    {
        $models = $this->model->where(function ($query) use ($idUsuario1, $idUsuario2) {
            $query->where(function ($q) use ($idUsuario1, $idUsuario2) {
                $q->where('id_usuario_remitente', $idUsuario1)
                  ->where('id_usuario_destinatario', $idUsuario2);
            })->orWhere(function ($q) use ($idUsuario1, $idUsuario2) {
                $q->where('id_usuario_remitente', $idUsuario2)
                  ->where('id_usuario_destinatario', $idUsuario1);
            });
        })
        ->orderBy('created_at', 'asc')
        ->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findByUsuarioRemitente(int $idUsuario): array
    {
        $models = $this->model->where('id_usuario_remitente', $idUsuario)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function findByUsuarioDestinatario(int $idUsuario): array
    {
        $models = $this->model->where('id_usuario_destinatario', $idUsuario)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function marcarComoLeido(int $idMensaje): bool
    {
        $model = $this->model->find($idMensaje);
        
        if (!$model) {
            return false;
        }

        return $model->update([
            'leido' => true,
            'fecha_leido' => now(),
        ]);
    }

    public function contarNoLeidos(int $idUsuario): int
    {
        return $this->model->where('id_usuario_destinatario', $idUsuario)
            ->where('leido', false)
            ->count();
    }

    public function find($id): ?Mensaje
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

    public function create(array $data): Mensaje
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

    private function toEntity(MensajeModel $model): Mensaje
    {
        $entity = new Mensaje(
            $model->id_usuario_remitente,
            $model->id_usuario_destinatario,
            $model->mensaje,
            $model->id_oferta,
            $model->leido ?? false,
            $model->fecha_leido ? $model->fecha_leido->toDateTimeString() : null,
            $model->created_at ? $model->created_at->toDateTimeString() : null,
            $model->updated_at ? $model->updated_at->toDateTimeString() : null
        );
        
        if ($model->id_mensaje) {
            $entity->setId($model->id_mensaje);
        }

        return $entity;
    }
}

