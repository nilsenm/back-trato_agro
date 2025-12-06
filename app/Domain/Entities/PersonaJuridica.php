<?php

namespace App\Domain\Entities;

class PersonaJuridica extends BaseEntity
{
    private string $ruc;
    private ?string $razonSocial;
    private ?string $domicilioFiscal;
    private ?string $nombreRepresentanteLegal;
    private ?string $celular;
    private ?string $pais;
    private ?int $departamento;
    private ?int $provincia;
    private ?int $distrito;
    private ?int $idUsuario;

    public function __construct(
        string $ruc,
        ?string $razonSocial = null,
        ?string $domicilioFiscal = null,
        ?string $nombreRepresentanteLegal = null,
        ?string $celular = null,
        ?string $pais = null,
        ?int $departamento = null,
        ?int $provincia = null,
        ?int $distrito = null,
        ?int $idUsuario = null
    ) {
        $this->ruc = $ruc;
        $this->razonSocial = $razonSocial;
        $this->domicilioFiscal = $domicilioFiscal;
        $this->nombreRepresentanteLegal = $nombreRepresentanteLegal;
        $this->celular = $celular;
        $this->pais = $pais;
        $this->departamento = $departamento;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->idUsuario = $idUsuario;
    }

    public function getRuc(): string
    {
        return $this->ruc;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function getDomicilioFiscal(): ?string
    {
        return $this->domicilioFiscal;
    }

    public function getNombreRepresentanteLegal(): ?string
    {
        return $this->nombreRepresentanteLegal;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function getDepartamento(): ?int
    {
        return $this->departamento;
    }

    public function getProvincia(): ?int
    {
        return $this->provincia;
    }

    public function getDistrito(): ?int
    {
        return $this->distrito;
    }

    public function toArray(): array
    {
        return [
            'ruc' => $this->ruc,
            'razon_social' => $this->razonSocial,
            'domicilio_fiscal' => $this->domicilioFiscal,
            'nombre_representante_legal' => $this->nombreRepresentanteLegal,
            'celular' => $this->celular,
            'pais' => $this->pais,
            'departamento' => $this->departamento,
            'provincia' => $this->provincia,
            'distrito' => $this->distrito,
            'id_usuario' => $this->idUsuario,
        ];
    }
}

