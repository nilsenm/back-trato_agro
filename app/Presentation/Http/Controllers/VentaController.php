<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\VentaService;
use App\Application\Services\DetalleVentaService;
use App\Application\Services\StockService;
use App\Application\Services\ProductoService;
use App\Application\Services\UnidadService;
use App\Application\Services\UsuarioService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VentaController extends BaseController
{
    public function __construct(
        private VentaService $ventaService,
        private DetalleVentaService $detalleVentaService,
        private StockService $stockService,
        private ProductoService $productoService,
        private UnidadService $unidadService,
        private UsuarioService $usuarioService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $ventas = $this->ventaService->getAll();
            return $this->successResponse(data: $ventas, message: 'Ventas obtenidas exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $venta = $this->ventaService->getById($id);
            
            if (!$venta) {
                return $this->notFoundResponse('Venta no encontrada');
            }

            return $this->successResponse(data: $venta, message: 'Venta obtenida exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'fecha' => 'nullable|date',
                'hora' => 'nullable|date_format:H:i:s',
                'id_usuario_compra' => 'nullable|integer|exists:usuario,id_usuario',
                'id_distrito' => 'required|integer',
            ]);

            if (!isset($validated['fecha'])) {
                $validated['fecha'] = now()->format('Y-m-d');
            }
            if (!isset($validated['hora'])) {
                $validated['hora'] = now()->format('H:i:s');
            }

            $venta = $this->ventaService->create($validated);
            return $this->successResponse(data: $venta, message: 'Venta creada exitosamente', code: 201, title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function byUsuario(int $idUsuario): JsonResponse
    {
        try {
            $ventas = $this->ventaService->findByUsuarioCompra($idUsuario);
            return $this->successResponse(data: $ventas, message: 'Ventas obtenidas exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function ultimaVenta(int $idUsuario): JsonResponse
    {
        try {
            $venta = $this->ventaService->getUltimaVenta($idUsuario);
            
            if (!$venta) {
                return $this->notFoundResponse('No se encontraron ventas');
            }

            return $this->successResponse(data: $venta, message: 'Última venta obtenida exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    /**
     * Obtener todas las compras del usuario autenticado con información detallada
     */
    public function misCompras(): JsonResponse
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

            $ventas = $this->ventaService->findByUsuarioCompra($user->id_usuario);
            
            if (empty($ventas)) {
                return $this->successResponse(
                    data: [],
                    message: 'No se encontraron compras',
                    title: 'Mis compras'
                );
            }

            // Obtener información del comprador
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

            // Enriquecer cada venta con información detallada
            $ventasDetalladas = [];
            foreach ($ventas as $venta) {
                // Obtener información completa del distrito (departamento, provincia, distrito)
                $ubicacion = DB::table('distrito as d')
                    ->join('provincia as p', 'd.id_provincia', '=', 'p.id_provincia')
                    ->join('departamento as dep', 'p.id_departamento', '=', 'dep.id_departamento')
                    ->where('d.id_distrito', $venta->getIdDistrito())
                    ->select(
                        'd.id_distrito',
                        'd.nombre as distrito',
                        'p.id_provincia',
                        'p.nombre as provincia',
                        'dep.id_departamento',
                        'dep.nombre as departamento'
                    )
                    ->first();

                $ventaArray = [
                    'id_venta' => $venta->getId(),
                    'fecha' => $venta->getFecha() ? $venta->getFecha()->format('Y-m-d') : null,
                    'hora' => $venta->getHora() ? $venta->getHora()->format('H:i:s') : null,
                    'id_usuario_compra' => $venta->getIdUsuarioCompra(),
                    'id_distrito' => $venta->getIdDistrito(),
                    'direccion' => $venta->getDireccion(),
                    'tipo_pago' => $venta->getTipoPago() ?? 'CONTRA_ENTREGA',
                    'estado' => $venta->getEstado(),
                ];

                // Agregar información de ubicación si existe
                if ($ubicacion) {
                    $ventaArray['ubicacion'] = [
                        'id_distrito' => $ubicacion->id_distrito,
                        'distrito' => $ubicacion->distrito,
                        'id_provincia' => $ubicacion->id_provincia,
                        'provincia' => $ubicacion->provincia,
                        'id_departamento' => $ubicacion->id_departamento,
                        'departamento' => $ubicacion->departamento,
                        'direccion_completa' => trim(
                            ($venta->getDireccion() ? $venta->getDireccion() . ', ' : '') .
                            ($ubicacion->distrito ?? '') . ', ' .
                            ($ubicacion->provincia ?? '') . ', ' .
                            ($ubicacion->departamento ?? '')
                        ),
                    ];
                }

                // Obtener detalles de venta
                $detallesVenta = $this->detalleVentaService->findByVenta($venta->getId());
                
                // Verificar que se obtengan todos los detalles
                if (empty($detallesVenta)) {
                    // Si no hay detalles, continuar con la siguiente venta
                    continue;
                }
                
                $items = [];
                $subtotal = 0;
                $total = 0;

                foreach ($detallesVenta as $detalle) {
                    $stock = $this->stockService->getById($detalle->getIdStock());
                    if (!$stock) {
                        continue;
                    }

                    // Obtener información del producto
                    $producto = null;
                    if ($stock->getIdProducto()) {
                        $producto = $this->productoService->getById($stock->getIdProducto());
                    }

                    // Calcular subtotal del item
                    $precio = $stock->getPrecio() ?? 0;
                    $subtotalItem = $precio * $detalle->getCantidad();
                    $subtotal += $subtotalItem;

                    // Preparar información del item
                    $itemInfo = [
                        'id_detalle_venta' => $detalle->getId(),
                        'id_stock' => $stock->getId(),
                        'cantidad' => $detalle->getCantidad(),
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

                        // Obtener información del vendedor (dueño del producto)
                        if ($producto->getIdUsuario()) {
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

                                $itemInfo['vendedor'] = $vendedorInfo;
                            }
                        }
                    }

                    // Obtener información de la unidad
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

                    $items[] = $itemInfo;
                }

                // Agregar resumen de la venta
                // El total es igual al subtotal (por ahora no hay descuentos ni impuestos)
                $total = $subtotal;
                
                $ventaArray['items'] = $items;
                $ventaArray['resumen'] = [
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'tipo_moneda' => 'PEN',
                    'cantidad_items' => count($items),
                ];

                $ventasDetalladas[] = $ventaArray;
            }

            return $this->successResponse(
                data: [
                    'comprador' => $compradorInfo,
                    'compras' => $ventasDetalladas,
                    'total_compras' => count($ventasDetalladas),
                ],
                message: 'Compras obtenidas exitosamente',
                title: 'Mis compras'
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
     * Obtener todas las ventas del usuario autenticado como vendedor
     * (ventas donde el usuario tiene productos vendidos)
     */
    public function misVentas(): JsonResponse
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

            // Buscar ventas donde el usuario es vendedor (a través de sus productos)
            // Join: venta -> detalle_venta -> stock -> producto -> id_usuario
            $ventasIds = DB::table('venta as v')
                ->join('detalle_venta as dv', 'v.id_venta', '=', 'dv.id_venta')
                ->join('stock as s', 'dv.id_stock', '=', 's.id_stock')
                ->join('producto as p', 's.id_producto', '=', 'p.id_producto')
                ->where('p.id_usuario', $user->id_usuario)
                ->distinct()
                ->pluck('v.id_venta')
                ->toArray();

            if (empty($ventasIds)) {
                return $this->successResponse(
                    data: [
                        'vendedor' => null,
                        'ventas' => [],
                        'total_ventas' => 0,
                    ],
                    message: 'No se encontraron ventas',
                    title: 'Mis ventas'
                );
            }

            // Obtener las ventas
            $ventas = $this->ventaService->getAll();
            $ventasVendedor = array_filter($ventas, function($venta) use ($ventasIds) {
                return in_array($venta->getId(), $ventasIds);
            });

            // Ordenar por id_venta descendente (más recientes primero)
            usort($ventasVendedor, function($a, $b) {
                return $b->getId() - $a->getId();
            });

            // Obtener información del vendedor
            $vendedorUsuario = $this->usuarioService->getById($user->id_usuario);
            $vendedorInfo = null;
            if ($vendedorUsuario) {
                $vendedorInfo = [
                    'id_usuario' => $vendedorUsuario->getId(),
                    'nombre' => $vendedorUsuario->getNombre(),
                    'username' => $vendedorUsuario->getUsername(),
                    'correo' => $vendedorUsuario->getCorreo(),
                    'documento' => $vendedorUsuario->getDocumento(),
                    'tipo_persona' => $vendedorUsuario->getTipoPersona(),
                ];

                // Buscar persona natural o jurídica del vendedor
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
                            'nombre_completo' => trim(($personaNatural->nombres ?? '') . ' ' . ($personaNatural->apellidos ?? '')),
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
                            'nombre_completo' => $personaJuridica->razon_social,
                            'documento' => $personaJuridica->ruc,
                            'tipo_documento' => 'RUC',
                        ];
                    }
                }
            }

            // Enriquecer cada venta con información detallada
            $ventasDetalladas = [];
            foreach ($ventasVendedor as $venta) {
                // Obtener información completa del distrito
                $ubicacion = DB::table('distrito as d')
                    ->join('provincia as p', 'd.id_provincia', '=', 'p.id_provincia')
                    ->join('departamento as dep', 'p.id_departamento', '=', 'dep.id_departamento')
                    ->where('d.id_distrito', $venta->getIdDistrito())
                    ->select(
                        'd.id_distrito',
                        'd.nombre as distrito',
                        'p.id_provincia',
                        'p.nombre as provincia',
                        'dep.id_departamento',
                        'dep.nombre as departamento'
                    )
                    ->first();

                $ventaArray = [
                    'id_venta' => $venta->getId(),
                    'fecha' => $venta->getFecha() ? $venta->getFecha()->format('Y-m-d') : null,
                    'hora' => $venta->getHora() ? $venta->getHora()->format('H:i:s') : null,
                    'id_usuario_compra' => $venta->getIdUsuarioCompra(),
                    'id_distrito' => $venta->getIdDistrito(),
                    'direccion' => $venta->getDireccion(),
                    'tipo_pago' => $venta->getTipoPago() ?? 'CONTRA_ENTREGA',
                    'estado' => $venta->getEstado(),
                ];

                // Agregar información de ubicación
                if ($ubicacion) {
                    $ventaArray['ubicacion'] = [
                        'id_distrito' => $ubicacion->id_distrito,
                        'distrito' => $ubicacion->distrito,
                        'id_provincia' => $ubicacion->id_provincia,
                        'provincia' => $ubicacion->provincia,
                        'id_departamento' => $ubicacion->id_departamento,
                        'departamento' => $ubicacion->departamento,
                        'direccion_completa' => trim(
                            ($venta->getDireccion() ? $venta->getDireccion() . ', ' : '') .
                            ($ubicacion->distrito ?? '') . ', ' .
                            ($ubicacion->provincia ?? '') . ', ' .
                            ($ubicacion->departamento ?? '')
                        ),
                    ];
                }

                // Obtener información del comprador (una vez por venta)
                $compradorInfo = null;
                if ($venta->getIdUsuarioCompra()) {
                    $compradorUsuario = $this->usuarioService->getById($venta->getIdUsuarioCompra());
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
                                    'documento' => $personaJuridica->ruc,
                                    'tipo_documento' => 'RUC',
                                ];
                            }
                        }
                    }
                }

                // Obtener detalles de venta solo de los productos del vendedor
                $detallesVenta = $this->detalleVentaService->findByVenta($venta->getId());
                $items = [];
                $subtotal = 0;
                $total = 0;

                foreach ($detallesVenta as $detalle) {
                    $stock = $this->stockService->getById($detalle->getIdStock());
                    if (!$stock) {
                        continue;
                    }

                    // Solo incluir items de productos del vendedor
                    $producto = null;
                    if ($stock->getIdProducto()) {
                        $producto = $this->productoService->getById($stock->getIdProducto());
                        if (!$producto || $producto->getIdUsuario() != $user->id_usuario) {
                            continue; // Saltar productos que no son del vendedor
                        }
                    }

                    // Calcular subtotal del item
                    $precio = $stock->getPrecio() ?? 0;
                    $subtotalItem = $precio * $detalle->getCantidad();
                    $subtotal += $subtotalItem;

                    // Preparar información del item
                    $itemInfo = [
                        'id_detalle_venta' => $detalle->getId(),
                        'id_stock' => $stock->getId(),
                        'cantidad' => $detalle->getCantidad(),
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

                    // Agregar información del comprador al item (ya obtenida antes del foreach)
                    if ($compradorInfo) {
                        $itemInfo['comprador'] = $compradorInfo;
                    }

                    // Obtener información de la unidad
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

                    $items[] = $itemInfo;
                }

                // El total es igual al subtotal
                $total = $subtotal;

                // Agregar resumen de la venta
                $ventaArray['items'] = $items;
                $ventaArray['comprador'] = $compradorInfo ?? null;
                $ventaArray['resumen'] = [
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'tipo_moneda' => 'PEN',
                    'cantidad_items' => count($items),
                ];

                $ventasDetalladas[] = $ventaArray;
            }

            return $this->successResponse(
                data: [
                    'vendedor' => $vendedorInfo,
                    'ventas' => $ventasDetalladas,
                    'total_ventas' => count($ventasDetalladas),
                ],
                message: 'Ventas obtenidas exitosamente',
                title: 'Mis ventas'
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
     * Cambiar el estado de una venta
     */
    public function cambiarEstado(Request $request, int $id): JsonResponse
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
                'estado' => 'required|string|in:PEDIDO,ENVIADO,ENTREGADO,CANCELADO',
            ]);

            $venta = $this->ventaService->getById($id);
            if (!$venta) {
                return $this->notFoundResponse('Venta no encontrada');
            }

            // Verificar que el usuario es el vendedor o el comprador
            $esVendedor = false;
            $esComprador = $venta->getIdUsuarioCompra() == $user->id_usuario;

            if (!$esComprador) {
                // Verificar si es vendedor
                $detallesVenta = $this->detalleVentaService->findByVenta($venta->getId());
                foreach ($detallesVenta as $detalle) {
                    $stock = $this->stockService->getById($detalle->getIdStock());
                    if ($stock && $stock->getIdProducto()) {
                        $producto = $this->productoService->getById($stock->getIdProducto());
                        if ($producto && $producto->getIdUsuario() == $user->id_usuario) {
                            $esVendedor = true;
                            break;
                        }
                    }
                }
            }

            if (!$esComprador && !$esVendedor) {
                return $this->errorResponse(
                    message: 'No tienes permiso para modificar esta venta',
                    code: 403,
                    codeError: '403',
                    title: 'Error de permisos'
                );
            }

            // Actualizar el estado
            $venta->setEstado($validated['estado']);
            $this->ventaService->update($id, ['estado' => $validated['estado']]);

            return $this->successResponse(
                data: [
                    'id_venta' => $venta->getId(),
                    'estado' => $venta->getEstado(),
                ],
                message: 'Estado de venta actualizado exitosamente',
                title: 'Estado actualizado'
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

