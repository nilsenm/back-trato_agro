<?php

namespace App\Domain\Entities;

class Oferta extends BaseEntity
{
    private int $idStock;
    private int $idUsuarioOfertante;
    private int $idUsuarioVendedor;
    private float $precioOfertado;
    private int $cantidad;
    private string $tipoMoneda;
    private string $estado; // PENDIENTE, ACEPTADA, RECHAZADA, CANCELADA
    private ?string $mensaje;
    private ?string $fechaRespuesta;

    public function __construct(
        int $idStock,
        int $idUsuarioOfertante,
        int $idUsuarioVendedor,
        float $precioOfertado,
        int $cantidad,
        string $tipoMoneda = 'PEN',
        string $estado = 'PENDIENTE',
        ?string $mensaje = null,
        ?string $fechaRespuesta = null
    ) {
        $this->idStock = $idStock;
        $this->idUsuarioOfertante = $idUsuarioOfertante;
        $this->idUsuarioVendedor = $idUsuarioVendedor;
        $this->precioOfertado = $precioOfertado;
        $this->cantidad = $cantidad;
        $this->tipoMoneda = $tipoMoneda;
        $this->estado = $estado;
        $this->mensaje = $mensaje;
        $this->fechaRespuesta = $fechaRespuesta;
    }

    public function getIdStock(): int
    {
        return $this->idStock;
    }

    public function setIdStock(int $idStock): void
    {
        $this->idStock = $idStock;
    }

    public function getIdUsuarioOfertante(): int
    {
        return $this->idUsuarioOfertante;
    }

    public function setIdUsuarioOfertante(int $idUsuarioOfertante): void
    {
        $this->idUsuarioOfertante = $idUsuarioOfertante;
    }

    public function getIdUsuarioVendedor(): int
    {
        return $this->idUsuarioVendedor;
    }

    public function setIdUsuarioVendedor(int $idUsuarioVendedor): void
    {
        $this->idUsuarioVendedor = $idUsuarioVendedor;
    }

    public function getPrecioOfertado(): float
    {
        return $this->precioOfertado;
    }

    public function setPrecioOfertado(float $precioOfertado): void
    {
        $this->precioOfertado = $precioOfertado;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function getTipoMoneda(): string
    {
        return $this->tipoMoneda;
    }

    public function setTipoMoneda(string $tipoMoneda): void
    {
        $this->tipoMoneda = $tipoMoneda;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(?string $mensaje): void
    {
        $this->mensaje = $mensaje;
    }

    public function getFechaRespuesta(): ?string
    {
        return $this->fechaRespuesta;
    }

    public function setFechaRespuesta(?string $fechaRespuesta): void
    {
        $this->fechaRespuesta = $fechaRespuesta;
    }

    public function estaPendiente(): bool
    {
        return $this->estado === 'PENDIENTE';
    }

    public function estaAceptada(): bool
    {
        return $this->estado === 'ACEPTADA';
    }

    public function toArray(): array
    {
        return [
            'id_oferta' => $this->getId(),
            'id_stock' => $this->idStock,
            'id_usuario_ofertante' => $this->idUsuarioOfertante,
            'id_usuario_vendedor' => $this->idUsuarioVendedor,
            'precio_ofertado' => $this->precioOfertado,
            'cantidad' => $this->cantidad,
            'tipo_moneda' => $this->tipoMoneda,
            'estado' => $this->estado,
            'mensaje' => $this->mensaje,
            'fecha_respuesta' => $this->fechaRespuesta,
        ];
    }
}

