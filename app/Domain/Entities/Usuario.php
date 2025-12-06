<?php

namespace App\Domain\Entities;

class Usuario extends BaseEntity
{
    private string $documento;
    private ?string $username;
    private ?string $nombre;
    private ?string $correo;
    private ?string $clave;
    private ?string $estado;
    private string $tipoVendedor;
    private ?string $tipoPersona;

    public function __construct(
        string $documento,
        string $tipoVendedor,
        ?string $nombre = null,
        ?string $correo = null,
        ?string $clave = null,
        ?string $estado = null,
        ?string $tipoPersona = null,
        ?string $username = null
    ) {
        $this->documento = $documento;
        $this->tipoVendedor = $tipoVendedor;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->estado = $estado;
        $this->tipoPersona = $tipoPersona;
        $this->username = $username;
    }

    public function getDocumento(): string
    {
        return $this->documento;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function getClave(): ?string
    {
        return $this->clave;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function getTipoVendedor(): string
    {
        return $this->tipoVendedor;
    }

    public function getTipoPersona(): ?string
    {
        return $this->tipoPersona;
    }

    public function getUsuario(): ?string
    {
        return $this->username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function toArray(): array
    {
        return [
            'id_usuario' => $this->getId(),
            'documento' => $this->documento,
            'username' => $this->username,
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'estado' => $this->estado,
            'tipo_vendedor' => $this->tipoVendedor,
            'tipo_persona' => $this->tipoPersona,
        ];
    }
}


