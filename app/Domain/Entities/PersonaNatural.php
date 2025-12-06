<?php

namespace App\Domain\Entities;

class PersonaNatural extends BaseEntity
{
    private string $dni;
    private ?string $nombres;
    private ?string $apellidos;
    private ?string $direccion;
    private ?string $celular;
    private ?string $pais;
    private ?int $departamento;
    private ?int $provincia;
    private ?int $distrito;
    private ?int $idUsuario;

    public function __construct(
        string $dni,
        ?string $nombres = null,
        ?string $apellidos = null,
        ?string $direccion = null,
        ?string $celular = null,
        ?string $pais = null,
        ?int $departamento = null,
        ?int $provincia = null,
        ?int $distrito = null,
        ?int $idUsuario = null
    ) {
        $this->dni = $dni;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->direccion = $direccion;
        $this->celular = $celular;
        $this->pais = $pais;
        $this->departamento = $departamento;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->idUsuario = $idUsuario;
    }

    public function getDni(): string
    {
        return $this->dni;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
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
            'dni' => $this->dni,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'direccion' => $this->direccion,
            'celular' => $this->celular,
            'pais' => $this->pais,
            'departamento' => $this->departamento,
            'provincia' => $this->provincia,
            'distrito' => $this->distrito,
            'id_usuario' => $this->idUsuario,
        ];
    }
}

