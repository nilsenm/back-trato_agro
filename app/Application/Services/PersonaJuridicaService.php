<?php

namespace App\Application\Services;

use App\Domain\Entities\PersonaJuridica;
use App\Domain\Interfaces\PersonaJuridicaRepositoryInterface;

class PersonaJuridicaService extends BaseService
{
    public function __construct(PersonaJuridicaRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByRuc(string $ruc): ?PersonaJuridica
    {
        return $this->repository->findByRuc($ruc);
    }

    public function update($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function updateAndGet(string $ruc, array $data): ?PersonaJuridica
    {
        $updated = $this->repository->update($ruc, $data);
        
        if (!$updated) {
            return null;
        }

        return $this->repository->findByRuc($ruc);
    }

    public function enlazarUsuario(string $ruc, int $idUsuario): bool
    {
        return $this->repository->update($ruc, ['id_usuario' => $idUsuario]);
    }
}




