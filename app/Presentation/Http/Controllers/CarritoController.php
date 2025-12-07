<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\CarritoService;
use App\Application\Services\StockService;
use App\Application\Services\ProductoService;
use App\Application\Services\UnidadService;
use App\Application\Services\DetalleVentaService;
use App\Application\Services\VentaService;
use App\Application\Services\UsuarioService;
use App\Application\Services\PersonaNaturalService;
use App\Application\Services\PersonaJuridicaService;
use Illuminate\Support\Facades\DB;
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
        private DetalleVentaService $detalleVentaService,
        private VentaService $ventaService,
        private UsuarioService $usuarioService,
        private PersonaNaturalService $personaNaturalService,
        private PersonaJuridicaService $personaJuridicaService
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

    /**
     * Finaliza la venta desde el carrito
     * Crea la venta, los detalles de venta, calcula el resumen y limpia el carrito
     */
    public function finalizarVenta(Request $request): JsonResponse
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
                'id_distrito' => 'required|integer|exists:distrito,id_distrito',
                'direccion' => 'nullable|string|max:500',
                'tipo_pago' => 'nullable|string|in:CONTRA_ENTREGA',
            ]);

            // Obtener items del carrito
            $items = $this->carritoService->findByUsuario($user->id_usuario);
            
            if (empty($items)) {
                return $this->errorResponse(
                    message: 'El carrito está vacío',
                    code: 400,
                    codeError: '400',
                    title: 'Carrito vacío'
                );
            }

            // Validar stock disponible y calcular resumen
            $resumen = [];
            $subtotal = 0;
            $total = 0;
            $itemsVenta = [];

            foreach ($items as $item) {
                $stock = $this->stockService->getById($item->getIdStock());
                if (!$stock) {
                    throw new \Exception("Stock no encontrado para el item del carrito");
                }

                // Verificar cantidad disponible
                $cantidadVendida = $this->detalleVentaService->getCantidadVendida($stock->getId());
                $cantidadDisponible = $stock->getCantidad() - $cantidadVendida;
                
                if ($item->getCantidad() > $cantidadDisponible) {
                    throw new \Exception("No hay suficiente stock disponible para uno de los productos. Disponible: {$cantidadDisponible}, solicitado: {$item->getCantidad()}");
                }

                // Obtener información del producto
                $producto = null;
                if ($stock->getIdProducto()) {
                    $producto = $this->productoService->getById($stock->getIdProducto());
                }

                // Calcular subtotal del item
                $precio = $stock->getPrecio() ?? 0;
                $subtotalItem = $precio * $item->getCantidad();
                $subtotal += $subtotalItem;
                $total += $subtotalItem;

                // Obtener información del vendedor (dueño del producto)
                $vendedorInfo = null;
                if ($producto && $producto->getIdUsuario()) {
                    $vendedorUsuario = $this->usuarioService->getById($producto->getIdUsuario());
                    if ($vendedorUsuario) {
                        $vendedorInfo = [
                            'id_usuario' => $vendedorUsuario->getId(),
                            'nombre' => $vendedorUsuario->getNombre(),
                            'username' => $vendedorUsuario->getUsername(),
                            'correo' => $vendedorUsuario->getCorreo(),
                            'documento' => $vendedorUsuario->getDocumento(),
                            'tipo_persona' => $vendedorUsuario->getTipoPersona(),
                        ];

                        // Buscar persona natural o jurídica
                        if ($vendedorUsuario->getTipoPersona() === 'N') {
                            $personaNatural = DB::table('persona_natural')
                                ->where('id_usuario', $vendedorUsuario->getId())
                                ->first();
                            if ($personaNatural) {
                                $vendedorInfo['persona'] = [
                                    'tipo' => 'NATURAL',
                                    'dni' => $personaNatural->dni,
                                    'nombres' => $personaNatural->nombres,
                                    'apellidos' => $personaNatural->apellidos,
                                    'documento' => $personaNatural->dni,
                                    'tipo_documento' => 'DNI',
                                ];
                            }
                        } elseif ($vendedorUsuario->getTipoPersona() === 'J') {
                            $personaJuridica = DB::table('persona_juridica')
                                ->where('id_usuario', $vendedorUsuario->getId())
                                ->first();
                            if ($personaJuridica) {
                                $vendedorInfo['persona'] = [
                                    'tipo' => 'JURIDICA',
                                    'ruc' => $personaJuridica->ruc,
                                    'razon_social' => $personaJuridica->razon_social,
                                    'documento' => $personaJuridica->ruc,
                                    'tipo_documento' => 'RUC',
                                ];
                            }
                        }
                    }
                }

                // Preparar información del item para el resumen
                $itemInfo = [
                    'id_carrito' => $item->getId(),
                    'id_stock' => $stock->getId(),
                    'cantidad' => $item->getCantidad(),
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotalItem,
                    'tipo_moneda' => $stock->getTipoMoneda() ?? 'PEN',
                ];

                if ($producto) {
                    $itemInfo['producto'] = [
                        'id_producto' => $producto->getId(),
                        'nombre' => $producto->getNombre(),
                        'descripcion' => $producto->getDescripcion(),
                        'imagen' => $producto->getImagen(),
                    ];
                }

                if ($vendedorInfo) {
                    $itemInfo['vendedor'] = $vendedorInfo;
                }

                if ($stock->getIdUnidad()) {
                    $unidad = $this->unidadService->getById($stock->getIdUnidad());
                    if ($unidad) {
                        if (is_array($unidad)) {
                            $itemInfo['unidad'] = $unidad;
                        } elseif (is_object($unidad)) {
                            $itemInfo['unidad'] = [
                                'id_unidad' => $unidad->id_unidad ?? $stock->getIdUnidad(),
                                'nombre' => $unidad->nombre ?? null,
                            ];
                        }
                    }
                }

                $itemsVenta[] = $itemInfo;
            }

            // Crear la venta
            $ventaData = [
                'id_usuario_compra' => $user->id_usuario,
                'id_distrito' => $validated['id_distrito'],
                'fecha' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i:s'),
                'estado' => 'PEDIDO',
                'direccion' => $validated['direccion'] ?? null,
                'tipo_pago' => $validated['tipo_pago'] ?? 'CONTRA_ENTREGA',
            ];

            $venta = $this->ventaService->create($ventaData);
            $idVenta = $venta->getId();

            // Crear detalles de venta para cada item
            $detallesVenta = [];
            foreach ($items as $item) {
                $detalle = $this->detalleVentaService->create([
                    'id_venta' => $idVenta,
                    'id_stock' => $item->getIdStock(),
                    'cantidad' => $item->getCantidad(),
                ]);
                $detallesVenta[] = $detalle->toArray();
            }

            // Limpiar el carrito
            $this->carritoService->limpiarCarrito($user->id_usuario);

            // Obtener información del comprador (usuario)
            $compradorUsuario = $this->usuarioService->getById($user->id_usuario);
            $compradorInfo = null;
            if ($compradorUsuario) {
                $compradorInfo = [
                    'id_usuario' => $compradorUsuario->getId(),
                    'nombre' => $compradorUsuario->getNombre(),
                    'username' => $compradorUsuario->getUsername(),
                    'correo' => $compradorUsuario->getCorreo(),
                    'documento' => $compradorUsuario->getDocumento(),
                    'tipo_persona' => $compradorUsuario->getTipoPersona(),
                ];

                // Buscar persona natural o jurídica del comprador
                if ($compradorUsuario->getTipoPersona() === 'N') {
                    $personaNatural = DB::table('persona_natural')
                        ->where('id_usuario', $compradorUsuario->getId())
                        ->first();
                    if ($personaNatural) {
                        $compradorInfo['persona'] = [
                            'tipo' => 'NATURAL',
                            'dni' => $personaNatural->dni,
                            'nombres' => $personaNatural->nombres,
                            'apellidos' => $personaNatural->apellidos,
                            'nombre_completo' => trim(($personaNatural->nombres ?? '') . ' ' . ($personaNatural->apellidos ?? '')),
                            'documento' => $personaNatural->dni,
                            'tipo_documento' => 'DNI',
                        ];
                    }
                } elseif ($compradorUsuario->getTipoPersona() === 'J') {
                    $personaJuridica = DB::table('persona_juridica')
                        ->where('id_usuario', $compradorUsuario->getId())
                        ->first();
                    if ($personaJuridica) {
                        $compradorInfo['persona'] = [
                            'tipo' => 'JURIDICA',
                            'ruc' => $personaJuridica->ruc,
                            'razon_social' => $personaJuridica->razon_social,
                            'nombre_completo' => $personaJuridica->razon_social,
                            'documento' => $personaJuridica->ruc,
                            'tipo_documento' => 'RUC',
                        ];
                    }
                }
            }

            // Preparar respuesta con resumen completo
            $resumenCompleto = [
                'venta' => [
                    'id_venta' => $idVenta,
                    'fecha' => $venta->getFecha() ? $venta->getFecha()->format('Y-m-d') : null,
                    'hora' => $venta->getHora() ? $venta->getHora()->format('H:i:s') : null,
                    'id_usuario_compra' => $venta->getIdUsuarioCompra(),
                    'id_distrito' => $venta->getIdDistrito(),
                    'direccion' => $venta->getDireccion(),
                    'tipo_pago' => $venta->getTipoPago() ?? 'CONTRA_ENTREGA',
                    'estado' => $venta->getEstado(),
                ],
                'comprador' => $compradorInfo,
                'items' => $itemsVenta,
                'resumen' => [
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'tipo_moneda' => 'PEN', // Por ahora solo PEN, se puede mejorar
                    'cantidad_items' => count($itemsVenta),
                ],
                'detalles_venta' => $detallesVenta,
            ];

            return $this->successResponse(
                data: $resumenCompleto,
                message: 'Venta finalizada exitosamente',
                code: 201,
                title: 'Venta finalizada'
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
}

