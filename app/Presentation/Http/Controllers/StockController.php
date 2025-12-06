<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\StockService;
use App\Application\Services\ProductoService;
use App\Application\Services\UnidadService;
use App\Application\Services\DetalleVentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends BaseController
{
    public function __construct(
        private StockService $stockService,
        private ProductoService $productoService,
        private UnidadService $unidadService,
        private DetalleVentaService $detalleVentaService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $stocks = $this->stockService->getAll();
            return $this->successResponse(data: $stocks, message: 'Stocks obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $stock = $this->stockService->getById($id);
            
            if (!$stock) {
                return $this->notFoundResponse('Stock no encontrado');
            }

            return $this->successResponse(
                data: $stock,
                message: 'Stock obtenido exitosamente',
                title: 'Detalle de stock'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'precio' => 'nullable|numeric|min:0',
                'imagen' => 'nullable|string|max:500',
                'id_usuario' => 'nullable|integer|exists:usuario,id_usuario',
                'id_producto' => 'nullable|integer|exists:producto,id_producto',
                'cantidad' => 'required|integer|min:0',
                'destacado' => 'nullable|boolean',
            ]);

            $stock = $this->stockService->create($validated);
            return $this->successResponse(data: $stock, message: 'Stock creado exitosamente', code: 201, title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'precio' => 'nullable|numeric|min:0',
                'imagen' => 'nullable|string|max:500',
                'id_usuario' => 'nullable|integer|exists:usuario,id_usuario',
                'id_producto' => 'nullable|integer|exists:producto,id_producto',
                'cantidad' => 'sometimes|integer|min:0',
                'destacado' => 'nullable|boolean',
            ]);

            $updated = $this->stockService->update($id, $validated);
            
            if (!$updated) {
                return $this->notFoundResponse('Stock no encontrado');
            }

            $stock = $this->stockService->getById($id);
            return $this->successResponse(data: $stock, message: 'Stock actualizado exitosamente', title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->stockService->delete($id);
            
            if (!$deleted) {
                return $this->notFoundResponse('Stock no encontrado');
            }

            return $this->successResponse(null, 'Stock eliminado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function byUsuario(int $idUsuario): JsonResponse
    {
        try {
            $stocks = $this->stockService->findByUsuario($idUsuario);
            return $this->successResponse(data: $stocks, message: 'Stocks obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function byProducto(int $idProducto): JsonResponse
    {
        try {
            $stocks = $this->stockService->findByProducto($idProducto);
            return $this->successResponse(data: $stocks, message: 'Stocks obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function conStock(): JsonResponse
    {
        try {
            $stocks = $this->stockService->findConStock();
            return $this->successResponse(data: $stocks, message: 'Stocks con disponibilidad obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    /**
     * Obtiene productos destacados con información completa (público - sin autenticación)
     */
    public function destacados(): JsonResponse
    {
        try {
            $stocks = $this->stockService->findDestacados();
            
            // Enriquecer cada stock con información completa del producto y unidad
            $productosDestacados = [];
            foreach ($stocks as $stock) {
                $stockArray = $stock->toArray();
                
                // Obtener información del producto
                if ($stock->getIdProducto()) {
                    $producto = $this->productoService->getById($stock->getIdProducto());
                    if ($producto) {
                        $stockArray['producto'] = $producto->toArray();
                    }
                }
                
                // Calcular cantidad disponible
                $cantidadVendida = $this->detalleVentaService->getCantidadVendida($stock->getId());
                $cantidadDisponible = $stock->getCantidad() - $cantidadVendida;
                
                $stockArray['cantidad_disponible'] = $cantidadDisponible;
                $stockArray['cantidad_vendida'] = $cantidadVendida;
                
                // Obtener información de la unidad
                if ($stock->getIdUnidad()) {
                    $unidad = $this->unidadService->getById($stock->getIdUnidad());
                    if ($unidad) {
                        if (is_object($unidad) && method_exists($unidad, 'toArray')) {
                            $stockArray['unidad'] = $unidad->toArray();
                        } elseif (is_array($unidad)) {
                            $stockArray['unidad'] = $unidad;
                        }
                    }
                }
                
                $productosDestacados[] = $stockArray;
            }

            return $this->successResponse(
                data: $productosDestacados,
                message: 'Productos destacados obtenidos exitosamente',
                title: 'Productos destacados'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
                codeError: '500',
                title: 'Error del servidor',
                exception: $e
            );
        }
    }
}

