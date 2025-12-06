<?php

namespace App\Domain\Entities;

class Mensaje extends BaseEntity
{
    private ?int $idOferta;
    private int $idUsuarioRemitente;
    private int $idUsuarioDestinatario;
    private string $mensaje;
    private bool $leido;
    private ?string $fechaLeido;
    private ?string $fechaEnviado;
    private ?string $fechaActualizado;

    public function __construct(
        int $idUsuarioRemitente,
        int $idUsuarioDestinatario,
        string $mensaje,
        ?int $idOferta = null,
        bool $leido = false,
        ?string $fechaLeido = null,
        ?string $fechaEnviado = null,
        ?string $fechaActualizado = null
    ) {
        $this->idOferta = $idOferta;
        $this->idUsuarioRemitente = $idUsuarioRemitente;
        $this->idUsuarioDestinatario = $idUsuarioDestinatario;
        $this->mensaje = $mensaje;
        $this->leido = $leido;
        $this->fechaLeido = $fechaLeido;
        $this->fechaEnviado = $fechaEnviado;
        $this->fechaActualizado = $fechaActualizado;
    }

    public function getIdOferta(): ?int
    {
        return $this->idOferta;
    }

    public function setIdOferta(?int $idOferta): void
    {
        $this->idOferta = $idOferta;
    }

    public function getIdUsuarioRemitente(): int
    {
        return $this->idUsuarioRemitente;
    }

    public function setIdUsuarioRemitente(int $idUsuarioRemitente): void
    {
        $this->idUsuarioRemitente = $idUsuarioRemitente;
    }

    public function getIdUsuarioDestinatario(): int
    {
        return $this->idUsuarioDestinatario;
    }

    public function setIdUsuarioDestinatario(int $idUsuarioDestinatario): void
    {
        $this->idUsuarioDestinatario = $idUsuarioDestinatario;
    }

    public function getMensaje(): string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): void
    {
        $this->mensaje = $mensaje;
    }

    public function isLeido(): bool
    {
        return $this->leido;
    }

    public function setLeido(bool $leido): void
    {
        $this->leido = $leido;
    }

    public function getFechaLeido(): ?string
    {
        return $this->fechaLeido;
    }

    public function setFechaLeido(?string $fechaLeido): void
    {
        $this->fechaLeido = $fechaLeido;
    }

    public function getFechaEnviado(): ?string
    {
        return $this->fechaEnviado;
    }

    public function setFechaEnviado(?string $fechaEnviado): void
    {
        $this->fechaEnviado = $fechaEnviado;
    }

    public function getFechaActualizado(): ?string
    {
        return $this->fechaActualizado;
    }

    public function setFechaActualizado(?string $fechaActualizado): void
    {
        $this->fechaActualizado = $fechaActualizado;
    }

    public function toArray(): array
    {
        return [
            'id_mensaje' => $this->getId(),
            'id_oferta' => $this->idOferta,
            'id_usuario_remitente' => $this->idUsuarioRemitente,
            'id_usuario_destinatario' => $this->idUsuarioDestinatario,
            'mensaje' => $this->mensaje,
            'leido' => $this->leido,
            'fecha_leido' => $this->fechaLeido,
            'fecha_enviado' => $this->fechaEnviado,
            'fecha_actualizado' => $this->fechaActualizado,
        ];
    }
}

