<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\UnidadRepositoryInterface;
use App\Infrastructure\Models\UnidadModel;
use Illuminate\Database\Eloquent\Collection;

class UnidadRepository extends BaseRepository implements UnidadRepositoryInterface
{
    public function __construct(UnidadModel $model)
    {
        parent::__construct($model);
    }

    public function all(array $columns = ['*']): array
    {
        return parent::all($columns);
    }
}








