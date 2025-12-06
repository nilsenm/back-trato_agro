<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Mensaje;

interface MensajeRepositoryInterface extends RepositoryInterface
{
    public function findByOferta(int $idOferta): array;
    public function findByUsuarios(int $idUsuario1, int $idUsuario2): array;
    public function findByUsuarioRemitente(int $idUsuario): array;
    public function findByUsuarioDestinatario(int $idUsuario): array;
    public function marcarComoLeido(int $idMensaje): bool;
    public function contarNoLeidos(int $idUsuario): int;
}

