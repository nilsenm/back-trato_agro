<?php

namespace App\Domain\Entities;

class Venta extends BaseEntity
{
    private ?\DateTime $fecha;
    private ?\DateTime $hora;
    private ?int $idUsuarioCompra;
    private int $idDistrito;
    private string $estado;
    private ?string $direccion;
    private ?string $tipoPago;

    public function __construct(
        int $idDistrito,
        ?\DateTime $fecha = null,
        ?\DateTime $hora = null,
        ?int $idUsuarioCompra = null,
        string $estado = 'PEDIDO',
        ?string $direccion = null,
        ?string $tipoPago = 'CONTRA_ENTREGA'
    ) {
        $this->idDistrito = $idDistrito;
        $this->fecha = $fecha ?? new \DateTime();
        $this->hora = $hora ?? new \DateTime();
        $this->idUsuarioCompra = $idUsuarioCompra;
        $this->estado = $estado;
        $this->direccion = $direccion;
        $this->tipoPago = $tipoPago;
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

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): void
    {
        $this->direccion = $direccion;
    }

    public function getTipoPago(): ?string
    {
        return $this->tipoPago;
    }

    public function setTipoPago(?string $tipoPago): void
    {
        $this->tipoPago = $tipoPago;
    }
}









