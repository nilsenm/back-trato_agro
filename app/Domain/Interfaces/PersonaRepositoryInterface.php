<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Persona;

interface PersonaRepositoryInterface extends RepositoryInterface
{
    public function findByDocumento(string $numeroDocumento, string $tipoDocumento): ?Persona;
}

