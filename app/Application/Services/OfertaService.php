<?php

namespace App\Application\Services;

use App\Domain\Interfaces\OfertaRepositoryInterface;
use App\Domain\Interfaces\StockRepositoryInterface;

class OfertaService extends BaseService
{
    public function __construct(
        OfertaRepositoryInterface $repository,
        private StockRepositoryInterface $stockRepository
    ) {
        parent::__construct($repository);
    }

    public function crearOferta(array $data): array
    {
        // Validar que el stock existe y recibe ofertas
        $stock = $this->stockRepository->find($data['id_stock']);
        if (!$stock) {
            throw new \Exception('Stock no encontrado');
        }

        if (!$stock->getRecibeOfertas()) {
            throw new \Exception('Este producto no acepta ofertas');
        }

        // Verificar que no haya una oferta pendiente del mismo usuario para el mismo stock
        $ofertasPendientes = $this->repository->findPendientesByStock($data['id_stock']);
        foreach ($ofertasPendientes as $oferta) {
            if ($oferta->getIdUsuarioOfertante() === $data['id_usuario_ofertante']) {
                throw new \Exception('Ya tienes una oferta pendiente para este producto');
            }
        }

        // Verificar que el usuario no esté haciendo oferta a su propio producto
        if ($stock->getIdUsuario() === $data['id_usuario_ofertante']) {
            throw new \Exception('No puedes hacer ofertas a tus propios productos');
        }

        // Crear la oferta
        $oferta = $this->repository->create([
            'id_stock' => $data['id_stock'],
            'id_usuario_ofertante' => $data['id_usuario_ofertante'],
            'id_usuario_vendedor' => $stock->getIdUsuario(),
            'precio_ofertado' => $data['precio_ofertado'],
            'cantidad' => $data['cantidad'],
            'tipo_moneda' => $data['tipo_moneda'] ?? 'PEN',
            'estado' => 'PENDIENTE',
            'mensaje' => $data['mensaje'] ?? null,
        ]);

        return $oferta->toArray();
    }

    public function findByStock(int $idStock): array
    {
        return $this->repository->findByStock($idStock);
    }

    public function findByUsuarioOfertante(int $idUsuario): array
    {
        return $this->repository->findByUsuarioOfertante($idUsuario);
    }

    public function findByUsuarioVendedor(int $idUsuario): array
    {
        return $this->repository->findByUsuarioVendedor($idUsuario);
    }

    public function aceptarOferta(int $idOferta, int $idUsuarioVendedor): bool
    {
        $oferta = $this->repository->find($idOferta);
        
        if (!$oferta) {
            throw new \Exception('Oferta no encontrada');
        }

        if ($oferta->getIdUsuarioVendedor() !== $idUsuarioVendedor) {
            throw new \Exception('No tienes permiso para aceptar esta oferta');
        }

        if (!$oferta->estaPendiente()) {
            throw new \Exception('Esta oferta ya fue procesada');
        }

        return $this->repository->aceptar($idOferta);
    }

    public function rechazarOferta(int $idOferta, int $idUsuarioVendedor): bool
    {
        $oferta = $this->repository->find($idOferta);
        
        if (!$oferta) {
            throw new \Exception('Oferta no encontrada');
        }

        if ($oferta->getIdUsuarioVendedor() !== $idUsuarioVendedor) {
            throw new \Exception('No tienes permiso para rechazar esta oferta');
        }

        if (!$oferta->estaPendiente()) {
            throw new \Exception('Esta oferta ya fue procesada');
        }

        return $this->repository->rechazar($idOferta);
    }

    public function cancelarOferta(int $idOferta, int $idUsuarioOfertante): bool
    {
        $oferta = $this->repository->find($idOferta);
        
        if (!$oferta) {
            throw new \Exception('Oferta no encontrada');
        }

        if ($oferta->getIdUsuarioOfertante() !== $idUsuarioOfertante) {
            throw new \Exception('No tienes permiso para cancelar esta oferta');
        }

        if (!$oferta->estaPendiente()) {
            throw new \Exception('Solo se pueden cancelar ofertas pendientes');
        }

        return $this->repository->cancelar($idOferta);
    }
}

