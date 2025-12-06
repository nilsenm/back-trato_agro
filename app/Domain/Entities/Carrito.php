<?php

namespace App\Domain\Entities;

class Carrito extends BaseEntity
{
    private int $idUsuario;
    private int $idStock;
    private int $cantidad;

    public function __construct(
        int $idUsuario,
        int $idStock,
        int $cantidad
    ) {
        $this->idUsuario = $idUsuario;
        $this->idStock = $idStock;
        $this->cantidad = $cantidad;
    }

    public function getIdUsuario(): int
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(int $idUsuario): void
    {
        $this->idUsuario = $idUsuario;
    }

    public function getIdStock(): int
    {
        return $this->idStock;
    }

    public function setIdStock(int $idStock): void
    {
        $this->idStock = $idStock;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function toArray(): array
    {
        return [
            'id_carrito' => $this->getId(),
            'id_usuario' => $this->idUsuario,
            'id_stock' => $this->idStock,
            'cantidad' => $this->cantidad,
        ];
    }
}

