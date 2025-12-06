<?php

namespace App\Domain\Entities;

class Producto extends BaseEntity
{
    private string $nombre;
    private ?string $descripcion;
    private string $imagen;
    private ?int $idSubcategoria;
    private ?int $idUsuario;
    private string $estado;

    public function __construct(string $nombre, string $imagen = '-', ?int $idSubcategoria = null, ?string $descripcion = null, ?int $idUsuario = null, string $estado = 'ACTIVO')
    {
        $this->nombre = $nombre;
        $this->imagen = $imagen;
        $this->idSubcategoria = $idSubcategoria;
        $this->descripcion = $descripcion;
        $this->idUsuario = $idUsuario;
        $this->estado = $estado;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getImagen(): string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): void
    {
        $this->imagen = $imagen;
    }

    public function getIdSubcategoria(): ?int
    {
        return $this->idSubcategoria;
    }

    public function setIdSubcategoria(?int $idSubcategoria): void
    {
        $this->idSubcategoria = $idSubcategoria;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(?int $idUsuario): void
    {
        $this->idUsuario = $idUsuario;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function toArray(): array
    {
        return [
            'id_producto' => $this->getId(),
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'imagen' => $this->imagen,
            'id_subcategoria' => $this->idSubcategoria,
            'id_usuario' => $this->idUsuario,
            'estado' => $this->estado,
        ];
    }
}

