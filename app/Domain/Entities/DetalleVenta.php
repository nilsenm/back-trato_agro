<?php

namespace App\Domain\Entities;

class DetalleVenta extends BaseEntity
{
    private int $cantidad;
    private ?int $idStock;
    private ?int $idVenta;

    public function __construct(int $cantidad, ?int $idStock = null, ?int $idVenta = null)
    {
        $this->cantidad = $cantidad;
        $this->idStock = $idStock;
        $this->idVenta = $idVenta;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function getIdStock(): ?int
    {
        return $this->idStock;
    }

    public function getIdVenta(): ?int
    {
        return $this->idVenta;
    }
}









