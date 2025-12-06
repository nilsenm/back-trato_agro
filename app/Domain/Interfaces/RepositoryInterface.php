<?php

namespace App\Domain\Interfaces;

interface RepositoryInterface
{
    public function all(array $columns = ['*']): array;

    public function find($id);

    public function findBy(string $field, $value);

    public function create(array $data);

    public function update($id, array $data): bool;

    public function delete($id): bool;
}

