<?php

namespace App\Application\Services;

use App\Domain\Entities\Producto;
use App\Domain\Entities\Stock;
use App\Domain\Interfaces\ProductoRepositoryInterface;
use App\Domain\Interfaces\StockRepositoryInterface;

class ProductoService extends BaseService
{
    public function __construct(
        ProductoRepositoryInterface $repository,
        private StockRepositoryInterface $stockRepository
    ) {
        parent::__construct($repository);
    }

    public function findByNombre(string $nombre): ?Producto
    {
        return $this->repository->findByNombre($nombre);
    }

    public function findBySubcategoria(int $idSubcategoria): array
    {
        return $this->repository->findBySubcategoria($idSubcategoria);
    }

    /**
     * Obtiene productos filtrados por subcategoría y usuario
     */
    public function findBySubcategoriaAndUsuario(int $idSubcategoria, int $idUsuario): array
    {
        return $this->repository->findBySubcategoriaAndUsuario($idSubcategoria, $idUsuario);
    }

    /**
     * Obtiene productos con paginación y filtros opcionales
     */
    public function listado(int $page = 1, int $perPage = 10, ?string $nombre = null, ?int $idSubcategoria = null): array
    {
        return $this->repository->listado($page, $perPage, $nombre, $idSubcategoria);
    }

    /**
     * Obtiene todos los productos sin paginación
     */
    public function todos(): array
    {
        return $this->repository->all();
    }

    /**
     * Registra un producto y su stock asociado al usuario en una sola operación
     */
    public function registrarProductoConStock(array $productoData, array $stockData, int $idUsuario): array
    {
        // Agregar id_usuario y estado por defecto al producto
        $productoData['id_usuario'] = $idUsuario;
        if (!isset($productoData['estado'])) {
            $productoData['estado'] = 'ACTIVO';
        }
        
        // Crear el producto
        $producto = $this->repository->create($productoData);
        
        // Crear el stock asociado al producto y usuario
        $stockData['id_producto'] = $producto->getId();
        $stockData['id_usuario'] = $idUsuario;
        $stock = $this->stockRepository->create($stockData);
        
        return [
            'producto' => $producto,
            'stock' => $stock
        ];
    }
}






