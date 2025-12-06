<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\CarritoService;
use App\Application\Services\StockService;
use App\Application\Services\ProductoService;
use App\Application\Services\UnidadService;
use App\Application\Services\DetalleVentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CarritoController extends BaseController
{
    public function __construct(
        private CarritoService $carritoService,
        private StockService $stockService,
        private ProductoService $productoService,
        private UnidadService $unidadService,
        private DetalleVentaService $detalleVentaService
    ) {}

    /**
     * Obtiene todos los items del carrito del usuario autenticado con información completa
     */
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $items = $this->carritoService->findByUsuario($user->id_usuario);
            
            // Enriquecer cada item con información completa del stock y producto
            $itemsCompletos = [];
            foreach ($items as $item) {
                $itemArray = $item->toArray();
                
                // Obtener información del stock
                $stock = $this->stockService->getById($item->getIdStock());
                if ($stock) {
                    $stockArray = $stock->toArray();
                    
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
                    
                    // Obtener información del producto
                    if ($stock->getIdProducto()) {
                        $producto = $this->productoService->getById($stock->getIdProducto());
                        if ($producto) {
                            $stockArray['producto'] = $producto->toArray();
                        }
                    }
                    
                    $itemArray['stock'] = $stockArray;
                }
                
                $itemsCompletos[] = $itemArray;
            }

            return $this->successResponse(
                data: $itemsCompletos,
                message: 'Carrito obtenido exitosamente',
                title: 'Carrito de compras'
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

    /**
     * Agrega un item al carrito o actualiza la cantidad si ya existe
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $validated = $request->validate([
                'id_stock' => 'required|integer|exists:stock,id_stock',
                'cantidad' => 'required|integer|min:1',
            ]);

            $item = $this->carritoService->agregarAlCarrito(
                $user->id_usuario,
                $validated['id_stock'],
                $validated['cantidad']
            );

            return $this->successResponse(
                data: $item,
                message: 'Item agregado al carrito exitosamente',
                code: 201,
                title: 'Item agregado'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
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

    /**
     * Actualiza la cantidad de un item en el carrito
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $validated = $request->validate([
                'cantidad' => 'required|integer|min:1',
            ]);

            $item = $this->carritoService->actualizarCantidad($id, $validated['cantidad']);
            
            if (!$item) {
                return $this->successResponse(
                    data: null,
                    message: 'Item eliminado del carrito (cantidad 0)',
                    title: 'Item actualizado'
                );
            }

            return $this->successResponse(
                data: $item,
                message: 'Cantidad actualizada exitosamente',
                title: 'Item actualizado'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
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

    /**
     * Elimina un item del carrito
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $deleted = $this->carritoService->eliminarDelCarrito($id);
            
            if (!$deleted) {
                return $this->notFoundResponse('Item no encontrado en el carrito');
            }

            return $this->successResponse(
                data: null,
                message: 'Item eliminado del carrito exitosamente',
                title: 'Item eliminado'
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

    /**
     * Limpia todo el carrito del usuario autenticado
     */
    public function limpiar(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $this->carritoService->limpiarCarrito($user->id_usuario);

            return $this->successResponse(
                data: null,
                message: 'Carrito limpiado exitosamente',
                title: 'Carrito limpiado'
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

