<?php

namespace App\Domain\Entities;

class Persona extends BaseEntity
{
    private string $numeroDocumento;
    private string $tipoDocumento; // 1=DNI, 6=RUC
    private ?string $nombres;
    private ?string $apellidoPaterno;
    private ?string $apellidoMaterno;
    private ?string $nombreCompleto;
    private ?string $razonSocial;
    private ?string $direccion;
    private ?string $ubigeo;
    private ?string $distrito;
    private ?string $provincia;
    private ?string $departamento;
    private ?string $estado;
    private ?string $condicion;
    private bool $esAgenteRetencion;
    private bool $esBuenContribuyente;
    private ?string $digitoVerificador;
    private ?array $datosCompletos;

    public function __construct(
        string $numeroDocumento,
        string $tipoDocumento,
        ?string $nombres = null,
        ?string $apellidoPaterno = null,
        ?string $apellidoMaterno = null,
        ?string $nombreCompleto = null,
        ?string $razonSocial = null,
        ?string $direccion = null,
        ?string $ubigeo = null,
        ?string $distrito = null,
        ?string $provincia = null,
        ?string $departamento = null,
        ?string $estado = null,
        ?string $condicion = null,
        bool $esAgenteRetencion = false,
        bool $esBuenContribuyente = false,
        ?string $digitoVerificador = null,
        ?array $datosCompletos = null
    ) {
        $this->numeroDocumento = $numeroDocumento;
        $this->tipoDocumento = $tipoDocumento;
        $this->nombres = $nombres;
        $this->apellidoPaterno = $apellidoPaterno;
        $this->apellidoMaterno = $apellidoMaterno;
        $this->nombreCompleto = $nombreCompleto;
        $this->razonSocial = $razonSocial;
        $this->direccion = $direccion;
        $this->ubigeo = $ubigeo;
        $this->distrito = $distrito;
        $this->provincia = $provincia;
        $this->departamento = $departamento;
        $this->estado = $estado;
        $this->condicion = $condicion;
        $this->esAgenteRetencion = $esAgenteRetencion;
        $this->esBuenContribuyente = $esBuenContribuyente;
        $this->digitoVerificador = $digitoVerificador;
        $this->datosCompletos = $datosCompletos;
    }

    public function getNumeroDocumento(): string
    {
        return $this->numeroDocumento;
    }

    public function getTipoDocumento(): string
    {
        return $this->tipoDocumento;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function getApellidoPaterno(): ?string
    {
        return $this->apellidoPaterno;
    }

    public function getApellidoMaterno(): ?string
    {
        return $this->apellidoMaterno;
    }

    public function getNombreCompleto(): ?string
    {
        return $this->nombreCompleto;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function getDatosCompletos(): ?array
    {
        return $this->datosCompletos;
    }

    public function getUbigeo(): ?string
    {
        return $this->ubigeo;
    }

    public function getDistrito(): ?string
    {
        return $this->distrito;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function getDepartamento(): ?string
    {
        return $this->departamento;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function getCondicion(): ?string
    {
        return $this->condicion;
    }

    public function getDigitoVerificador(): ?string
    {
        return $this->digitoVerificador;
    }
}

