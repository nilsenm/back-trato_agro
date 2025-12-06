<?php

namespace App\Application\Services;

use App\Domain\Interfaces\UnidadRepositoryInterface;

class UnidadService extends BaseService
{
    public function __construct(UnidadRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}









