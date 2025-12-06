<?php

namespace App\Domain\Entities;

class Categoria extends BaseEntity
{
    private string $nombre;
    private ?string $icono;

    public function __construct(string $nombre, ?string $icono = null)
    {
        $this->nombre = $nombre;
        $this->icono = $icono;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getIcono(): ?string
    {
        return $this->icono;
    }

    public function setIcono(?string $icono): void
    {
        $this->icono = $icono;
    }

    public function toArray(): array
    {
        return [
            'id_categoria' => $this->getId(),
            'nombre' => $this->nombre,
            'icono' => $this->icono,
        ];
    }
}




