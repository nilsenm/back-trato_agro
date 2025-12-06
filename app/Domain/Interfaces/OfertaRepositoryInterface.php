<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Oferta;

interface OfertaRepositoryInterface extends RepositoryInterface
{
    public function findByStock(int $idStock): array;
    public function findByUsuarioOfertante(int $idUsuario): array;
    public function findByUsuarioVendedor(int $idUsuario): array;
    public function findPendientesByStock(int $idStock): array;
    public function aceptar(int $idOferta): bool;
    public function rechazar(int $idOferta): bool;
    public function cancelar(int $idOferta): bool;
}

