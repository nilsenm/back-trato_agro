<?php

namespace App\Domain\Entities;

class Subcategoria extends BaseEntity
{
    private string $nombre;
    private int $idCategoria;

    public function __construct(string $nombre, int $idCategoria)
    {
        $this->nombre = $nombre;
        $this->idCategoria = $idCategoria;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getIdCategoria(): int
    {
        return $this->idCategoria;
    }

    public function setIdCategoria(int $idCategoria): void
    {
        $this->idCategoria = $idCategoria;
    }

    public function toArray(): array
    {
        return [
            'id_subcategoria' => $this->getId(),
            'nombre' => $this->nombre,
            'id_categoria' => $this->idCategoria,
        ];
    }
}




