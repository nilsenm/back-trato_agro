<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Usuario;
use App\Domain\Interfaces\UsuarioRepositoryInterface;
use App\Infrastructure\Models\UsuarioModel;

class UsuarioRepository extends BaseRepository implements UsuarioRepositoryInterface
{
    public function __construct(UsuarioModel $model)
    {
        parent::__construct($model);
    }

    public function findByDocumento(string $documento): ?Usuario
    {
        $model = $this->model->where('documento', $documento)->first();
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByCorreo(string $correo): ?Usuario
    {
        $model = $this->model->where('correo', $correo)->first();
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByUsuario(string $username): ?Usuario
    {
        $model = $this->model->where('username', $username)->first();
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function find($id): ?Usuario
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

    public function create(array $data): Usuario
    {
        // Hash de contraseña si está presente
        if (isset($data['clave'])) {
            $data['clave'] = \Illuminate\Support\Facades\Hash::make($data['clave']);
        }
        
        $model = parent::create($data);
        return $this->toEntity($model);
    }

    private function toEntity(UsuarioModel $model): Usuario
    {
        $entity = new Usuario(
            $model->documento,
            $model->tipo_vendedor,
            $model->nombre,
            $model->correo,
            $model->clave,
            $model->estado,
            $model->tipo_persona,
            $model->username
        );
        
        if ($model->id_usuario) {
            $entity->setId($model->id_usuario);
        }

        return $entity;
    }
}


