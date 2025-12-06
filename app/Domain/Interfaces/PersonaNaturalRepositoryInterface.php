<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\PersonaNatural;

interface PersonaNaturalRepositoryInterface extends RepositoryInterface
{
    public function findByDni(string $dni): ?PersonaNatural;
}

