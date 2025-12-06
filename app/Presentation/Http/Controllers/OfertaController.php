<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\OfertaService;
use App\Application\Services\UsuarioService;
use App\Application\Services\ProductoService;
use App\Application\Services\StockService;
use App\Application\Services\UnidadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OfertaController extends BaseController
{
    public function __construct(
        private OfertaService $ofertaService,
        private UsuarioService $usuarioService,
        private ProductoService $productoService,
        private StockService $stockService,
        private UnidadService $unidadService
    ) {}

    /**
     * Crear una nueva oferta
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
                'precio_ofertado' => 'required|numeric|min:0',
                'cantidad' => 'required|integer|min:1',
                'tipo_moneda' => 'nullable|string|in:PEN,USD',
                'mensaje' => 'nullable|string|max:1000',
            ]);

            $oferta = $this->ofertaService->crearOferta([
                'id_stock' => $validated['id_stock'],
                'id_usuario_ofertante' => $user->id_usuario,
                'precio_ofertado' => $validated['precio_ofertado'],
                'cantidad' => $validated['cantidad'],
                'tipo_moneda' => $validated['tipo_moneda'] ?? 'PEN',
                'mensaje' => $validated['mensaje'] ?? null,
            ]);

            return $this->successResponse(
                data: $oferta,
                message: 'Oferta creada exitosamente',
                code: 201,
                title: 'Oferta creada'
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
     * Obtener ofertas de un stock/producto
     */
    public function byStock(int $idStock): JsonResponse
    {
        try {
            $ofertas = $this->ofertaService->findByStock($idStock);
            return $this->successResponse(
                data: $ofertas,
                message: 'Ofertas obtenidas exitosamente',
                title: 'Ofertas del producto'
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
     * Obtener ofertas enviadas por el usuario autenticado
     */
    public function misOfertas(): JsonResponse
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

            $ofertas = $this->ofertaService->findByUsuarioOfertante($user->id_usuario);
            
            // Enriquecer cada oferta con información completa
            $ofertasEnriquecidas = [];
            foreach ($ofertas as $oferta) {
                $ofertaArray = $oferta->toArray();
                
                // Obtener información del stock
                if ($oferta->getIdStock()) {
                    $stock = $this->stockService->getById($oferta->getIdStock());
                    if ($stock) {
                        $stockArray = $stock->toArray();
                        
                        // Obtener información del producto
                        if ($stock->getIdProducto()) {
                            $producto = $this->productoService->getById($stock->getIdProducto());
                            if ($producto) {
                                $stockArray['producto'] = $producto->toArray();
                            }
                        }
                        
                        // Obtener información de la unidad
                        if ($stock->getIdUnidad()) {
                            $unidad = $this->unidadService->getById($stock->getIdUnidad());
                            if ($unidad) {
                                if (is_array($unidad)) {
                                    $stockArray['unidad'] = $unidad;
                                } elseif (is_object($unidad)) {
                                    // Si es un modelo Eloquent
                                    $stockArray['unidad'] = [
                                        'id_unidad' => $unidad->id_unidad ?? $stock->getIdUnidad(),
                                        'nombre' => $unidad->nombre ?? null,
                                    ];
                                }
                            }
                        }
                        
                        $ofertaArray['stock'] = $stockArray;
                    }
                }
                
                // Obtener información del comprador (ofertante - tú)
                if ($oferta->getIdUsuarioOfertante()) {
                    $comprador = $this->usuarioService->getById($oferta->getIdUsuarioOfertante());
                    if ($comprador) {
                        $ofertaArray['comprador'] = [
                            'id_usuario' => $comprador->getId(),
                            'nombre' => $comprador->getNombre(),
                            'username' => $comprador->getUsername(),
                            'correo' => $comprador->getCorreo(),
                            'documento' => $comprador->getDocumento(),
                        ];
                    }
                }
                
                // Obtener información del vendedor
                if ($oferta->getIdUsuarioVendedor()) {
                    $vendedor = $this->usuarioService->getById($oferta->getIdUsuarioVendedor());
                    if ($vendedor) {
                        $ofertaArray['vendedor'] = [
                            'id_usuario' => $vendedor->getId(),
                            'nombre' => $vendedor->getNombre(),
                            'username' => $vendedor->getUsername(),
                            'correo' => $vendedor->getCorreo(),
                            'documento' => $vendedor->getDocumento(),
                        ];
                    }
                }
                
                $ofertasEnriquecidas[] = $ofertaArray;
            }
            
            return $this->successResponse(
                data: $ofertasEnriquecidas,
                message: 'Ofertas obtenidas exitosamente',
                title: 'Mis ofertas'
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
     * Obtener ofertas recibidas por el usuario autenticado
     */
    public function ofertasRecibidas(): JsonResponse
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

            $ofertas = $this->ofertaService->findByUsuarioVendedor($user->id_usuario);
            
            // Enriquecer cada oferta con información completa
            $ofertasEnriquecidas = [];
            foreach ($ofertas as $oferta) {
                $ofertaArray = $oferta->toArray();
                
                // Obtener información del stock
                if ($oferta->getIdStock()) {
                    $stock = $this->stockService->getById($oferta->getIdStock());
                    if ($stock) {
                        $stockArray = $stock->toArray();
                        
                        // Obtener información del producto
                        if ($stock->getIdProducto()) {
                            $producto = $this->productoService->getById($stock->getIdProducto());
                            if ($producto) {
                                $stockArray['producto'] = $producto->toArray();
                            }
                        }
                        
                        // Obtener información de la unidad
                        if ($stock->getIdUnidad()) {
                            $unidad = $this->unidadService->getById($stock->getIdUnidad());
                            if ($unidad) {
                                if (is_array($unidad)) {
                                    $stockArray['unidad'] = $unidad;
                                } elseif (is_object($unidad)) {
                                    // Si es un modelo Eloquent
                                    $stockArray['unidad'] = [
                                        'id_unidad' => $unidad->id_unidad ?? $stock->getIdUnidad(),
                                        'nombre' => $unidad->nombre ?? null,
                                    ];
                                }
                            }
                        }
                        
                        $ofertaArray['stock'] = $stockArray;
                    }
                }
                
                // Obtener información del comprador (ofertante)
                if ($oferta->getIdUsuarioOfertante()) {
                    $comprador = $this->usuarioService->getById($oferta->getIdUsuarioOfertante());
                    if ($comprador) {
                        $ofertaArray['comprador'] = [
                            'id_usuario' => $comprador->getId(),
                            'nombre' => $comprador->getNombre(),
                            'username' => $comprador->getUsername(),
                            'correo' => $comprador->getCorreo(),
                            'documento' => $comprador->getDocumento(),
                        ];
                    }
                }
                
                // Obtener información del vendedor
                if ($oferta->getIdUsuarioVendedor()) {
                    $vendedor = $this->usuarioService->getById($oferta->getIdUsuarioVendedor());
                    if ($vendedor) {
                        $ofertaArray['vendedor'] = [
                            'id_usuario' => $vendedor->getId(),
                            'nombre' => $vendedor->getNombre(),
                            'username' => $vendedor->getUsername(),
                            'correo' => $vendedor->getCorreo(),
                            'documento' => $vendedor->getDocumento(),
                        ];
                    }
                }
                
                $ofertasEnriquecidas[] = $ofertaArray;
            }
            
            return $this->successResponse(
                data: $ofertasEnriquecidas,
                message: 'Ofertas recibidas obtenidas exitosamente',
                title: 'Ofertas recibidas'
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
     * Aceptar una oferta
     */
    public function aceptar(int $id): JsonResponse
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

            $aceptada = $this->ofertaService->aceptarOferta($id, $user->id_usuario);
            
            if (!$aceptada) {
                return $this->notFoundResponse('Oferta no encontrada');
            }

            $oferta = $this->ofertaService->getById($id);
            return $this->successResponse(
                data: $oferta->toArray(),
                message: 'Oferta aceptada exitosamente',
                title: 'Oferta aceptada'
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
     * Rechazar una oferta
     */
    public function rechazar(int $id): JsonResponse
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

            $rechazada = $this->ofertaService->rechazarOferta($id, $user->id_usuario);
            
            if (!$rechazada) {
                return $this->notFoundResponse('Oferta no encontrada');
            }

            $oferta = $this->ofertaService->getById($id);
            return $this->successResponse(
                data: $oferta->toArray(),
                message: 'Oferta rechazada exitosamente',
                title: 'Oferta rechazada'
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
     * Cancelar una oferta (solo el ofertante puede cancelar)
     */
    public function cancelar(int $id): JsonResponse
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

            $cancelada = $this->ofertaService->cancelarOferta($id, $user->id_usuario);
            
            if (!$cancelada) {
                return $this->notFoundResponse('Oferta no encontrada');
            }

            $oferta = $this->ofertaService->getById($id);
            return $this->successResponse(
                data: $oferta->toArray(),
                message: 'Oferta cancelada exitosamente',
                title: 'Oferta cancelada'
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
     * Obtener una oferta por ID
     */
    public function show(int $id): JsonResponse
    {
        try {
            $oferta = $this->ofertaService->getById($id);
            
            if (!$oferta) {
                return $this->notFoundResponse('Oferta no encontrada');
            }

            return $this->successResponse(
                data: $oferta->toArray(),
                message: 'Oferta obtenida exitosamente',
                title: 'Detalle de oferta'
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

