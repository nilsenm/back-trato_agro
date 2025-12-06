<?php

namespace App\Domain\Entities;

class Stock extends BaseEntity
{
    private ?float $precio;
    private ?string $imagen;
    private ?int $idUsuario;
    private ?int $idProducto;
    private int $cantidad;
    private ?int $idUnidad;
    private string $tipoMoneda;
    private bool $recibeOfertas;

    public function __construct(
        int $cantidad,
        ?float $precio = null,
        ?string $imagen = null,
        ?int $idUsuario = null,
        ?int $idProducto = null,
        ?int $idUnidad = null,
        string $tipoMoneda = 'PEN',
        bool $recibeOfertas = false
    ) {
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->imagen = $imagen;
        $this->idUsuario = $idUsuario;
        $this->idProducto = $idProducto;
        $this->idUnidad = $idUnidad;
        $this->tipoMoneda = $tipoMoneda;
        $this->recibeOfertas = $recibeOfertas;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(?float $precio): void
    {
        $this->precio = $precio;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): void
    {
        $this->imagen = $imagen;
    }

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function getIdProducto(): ?int
    {
        return $this->idProducto;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function tieneStock(): bool
    {
        return $this->cantidad > 0;
    }

    public function getIdUnidad(): ?int
    {
        return $this->idUnidad;
    }

    public function setIdUnidad(?int $idUnidad): void
    {
        $this->idUnidad = $idUnidad;
    }

    public function getTipoMoneda(): string
    {
        return $this->tipoMoneda;
    }

    public function setTipoMoneda(string $tipoMoneda): void
    {
        $this->tipoMoneda = $tipoMoneda;
    }

    public function getRecibeOfertas(): bool
    {
        return $this->recibeOfertas;
    }

    public function setRecibeOfertas(bool $recibeOfertas): void
    {
        $this->recibeOfertas = $recibeOfertas;
    }

    public function toArray(): array
    {
        return [
            'id_stock' => $this->getId(),
            'precio' => $this->precio,
            'imagen' => $this->imagen,
            'id_usuario' => $this->idUsuario,
            'id_producto' => $this->idProducto,
            'cantidad' => $this->cantidad,
            'id_unidad' => $this->idUnidad,
            'tipo_moneda' => $this->tipoMoneda,
            'recibe_ofertas' => $this->recibeOfertas,
        ];
    }
}






