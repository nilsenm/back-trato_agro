<?php

namespace App\Application\Services;

use App\Domain\Entities\Carrito;
use App\Domain\Interfaces\CarritoRepositoryInterface;
use App\Domain\Interfaces\StockRepositoryInterface;
use App\Domain\Interfaces\DetalleVentaRepositoryInterface;

class CarritoService extends BaseService
{
    public function __construct(
        CarritoRepositoryInterface $repository,
        private StockRepositoryInterface $stockRepository,
        private DetalleVentaRepositoryInterface $detalleVentaRepository
    ) {
        parent::__construct($repository);
    }

    public function findByUsuario(int $idUsuario): array
    {
        return $this->repository->findByUsuario($idUsuario);
    }

    public function agregarAlCarrito(int $idUsuario, int $idStock, int $cantidad): array
    {
        // Verificar que el stock existe
        $stock = $this->stockRepository->find($idStock);
        if (!$stock) {
            throw new \Exception('Stock no encontrado');
        }

        // Calcular cantidad disponible
        $cantidadVendida = $this->detalleVentaRepository->getCantidadVendida($idStock);
        $cantidadDisponible = $stock->getCantidad() - $cantidadVendida;

        // Verificar si ya existe en el carrito
        $carritoExistente = $this->repository->findByUsuarioAndStock($idUsuario, $idStock);
        
        if ($carritoExistente) {
            // Calcular nueva cantidad total
            $nuevaCantidad = $carritoExistente->getCantidad() + $cantidad;
            
            // Verificar que no exceda la cantidad disponible
            if ($nuevaCantidad > $cantidadDisponible) {
                throw new \Exception("No hay suficiente stock disponible. Disponible: {$cantidadDisponible}, solicitado: {$nuevaCantidad}");
            }
            
            // Actualizar cantidad
            $this->repository->update($carritoExistente->getId(), ['cantidad' => $nuevaCantidad]);
            return $this->repository->find($carritoExistente->getId())->toArray();
        } else {
            // Verificar que la cantidad no exceda lo disponible
            if ($cantidad > $cantidadDisponible) {
                throw new \Exception("No hay suficiente stock disponible. Disponible: {$cantidadDisponible}, solicitado: {$cantidad}");
            }
            
            // Crear nuevo item
            $carrito = $this->repository->create([
                'id_usuario' => $idUsuario,
                'id_stock' => $idStock,
                'cantidad' => $cantidad,
            ]);
            return $carrito->toArray();
        }
    }

    public function actualizarCantidad(int $idCarrito, int $cantidad): ?array
    {
        $carrito = $this->repository->find($idCarrito);
        if (!$carrito) {
            return null;
        }

        if ($cantidad <= 0) {
            // Si la cantidad es 0 o menor, eliminar del carrito
            $this->repository->delete($idCarrito);
            return null;
        }

        // Verificar stock disponible
        $stock = $this->stockRepository->find($carrito->getIdStock());
        if ($stock) {
            $cantidadVendida = $this->detalleVentaRepository->getCantidadVendida($stock->getId());
            $cantidadDisponible = $stock->getCantidad() - $cantidadVendida;
            
            // Verificar que no exceda la cantidad disponible
            if ($cantidad > $cantidadDisponible) {
                throw new \Exception("No hay suficiente stock disponible. Disponible: {$cantidadDisponible}, solicitado: {$cantidad}");
            }
        }

        $updated = $this->repository->update($idCarrito, ['cantidad' => $cantidad]);
        if (!$updated) {
            return null;
        }

        $carritoActualizado = $this->repository->find($idCarrito);
        return $carritoActualizado ? $carritoActualizado->toArray() : null;
    }

    public function eliminarDelCarrito(int $idCarrito): bool
    {
        return $this->repository->delete($idCarrito);
    }

    public function limpiarCarrito(int $idUsuario): bool
    {
        return $this->repository->deleteByUsuario($idUsuario);
    }
}

