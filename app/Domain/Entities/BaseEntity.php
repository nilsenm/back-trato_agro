<?php

namespace App\Domain\Entities;

abstract class BaseEntity
{
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

