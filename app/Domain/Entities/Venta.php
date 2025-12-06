<?php

namespace App\Domain\Entities;

class Venta extends BaseEntity
{
    private ?\DateTime $fecha;
    private ?\DateTime $hora;
    private ?int $idUsuarioCompra;
    private int $idDistrito;

    public function __construct(
        int $idDistrito,
        ?\DateTime $fecha = null,
        ?\DateTime $hora = null,
        ?int $idUsuarioCompra = null
    ) {
        $this->idDistrito = $idDistrito;
        $this->fecha = $fecha ?? new \DateTime();
        $this->hora = $hora ?? new \DateTime();
        $this->idUsuarioCompra = $idUsuarioCompra;
    }

    public function getFecha(): ?\DateTime
    {
        return $this->fecha;
    }

    public function getHora(): ?\DateTime
    {
        return $this->hora;
    }

    public function getIdUsuarioCompra(): ?int
    {
        return $this->idUsuarioCompra;
    }

    public function getIdDistrito(): int
    {
        return $this->idDistrito;
    }
}









