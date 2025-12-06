<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*']): array
    {
        if ($columns === ['*']) {
            return $this->model->all()->toArray();
        }
        return $this->model->get($columns)->toArray();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findBy(string $field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        $model = $this->find($id);
        
        if (!$model) {
            return false;
        }

        return $model->update($data);
    }

    public function delete($id): bool
    {
        $model = $this->find($id);
        
        if (!$model) {
            return false;
        }

        return $model->delete();
    }
}

