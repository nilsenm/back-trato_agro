<?php

namespace App\Application\Services;

use App\Domain\Entities\Persona;
use App\Domain\Interfaces\PersonaRepositoryInterface;

class PersonaService extends BaseService
{
    public function __construct(PersonaRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByDocumento(string $numeroDocumento, string $tipoDocumento): ?Persona
    {
        return $this->repository->findByDocumento($numeroDocumento, $tipoDocumento);
    }
}








