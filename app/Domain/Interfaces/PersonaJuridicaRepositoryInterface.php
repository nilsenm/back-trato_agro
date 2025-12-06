<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\PersonaJuridica;

interface PersonaJuridicaRepositoryInterface extends RepositoryInterface
{
    public function findByRuc(string $ruc): ?PersonaJuridica;
}









