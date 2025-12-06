<?php

namespace App\Application\Services;

use App\Domain\Entities\PersonaNatural;
use App\Domain\Interfaces\PersonaNaturalRepositoryInterface;

class PersonaNaturalService extends BaseService
{
    public function __construct(PersonaNaturalRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByDni(string $dni): ?PersonaNatural
    {
        return $this->repository->findByDni($dni);
    }

    public function update($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function updateAndGet(string $dni, array $data): ?PersonaNatural
    {
        $updated = $this->repository->update($dni, $data);
        
        if (!$updated) {
            return null;
        }

        return $this->repository->findByDni($dni);
    }

    public function enlazarUsuario(string $dni, int $idUsuario): bool
    {
        return $this->repository->update($dni, ['id_usuario' => $idUsuario]);
    }
}

