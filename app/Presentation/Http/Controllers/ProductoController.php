<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\ImageService;
use App\Application\Services\ProductoService;
use App\Application\Services\StockService;
use App\Application\Services\SubcategoriaService;
use App\Application\Services\CategoriaService;
use App\Application\Services\UnidadService;
use App\Application\Services\DetalleVentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductoController extends BaseController
{
    public function __construct(
        private ProductoService $productoService,
        private ImageService $imageService,
        private StockService $stockService,
        private SubcategoriaService $subcategoriaService,
        private CategoriaService $categoriaService,
        private UnidadService $unidadService,
        private DetalleVentaService $detalleVentaService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $productos = $this->productoService->getAll();
            return $this->successResponse(data: $productos, message: 'Productos obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    /**
     * Obtiene todos los productos sin paginación
     */
    public function todos(): JsonResponse
    {
        try {
            $productos = $this->productoService->todos();
            return $this->successResponse(
                data: $productos,
                message: 'Todos los productos obtenidos exitosamente',
                title: 'Lista completa de productos'
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
     * Obtiene productos con paginación y filtros opcionales
     */
    public function listado(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
                'nombre' => 'nullable|string|max:300',
                'id_subcategoria' => 'nullable|integer|exists:subcategoria,id_subcategoria',
            ]);

            $page = $validated['page'] ?? 1;
            $perPage = $validated['per_page'] ?? 10;
            $nombre = $validated['nombre'] ?? null;
            $idSubcategoria = $validated['id_subcategoria'] ?? null;

            $resultado = $this->productoService->listado($page, $perPage, $nombre, $idSubcategoria);

            return $this->successResponse(
                data: $resultado['data'],
                message: 'Productos obtenidos exitosamente',
                title: 'Listado de productos',
                otherData: [
                    'pagination' => [
                        'current_page' => $resultado['current_page'],
                        'per_page' => $resultado['per_page'],
                        'total' => $resultado['total'],
                        'last_page' => $resultado['last_page'],
                        'from' => $resultado['from'],
                        'to' => $resultado['to'],
                    ]
                ]
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

    public function show(int $id): JsonResponse
    {
        try {
            $producto = $this->productoService->getById($id);
            
            if (!$producto) {
                return $this->notFoundResponse('Producto no encontrado');
            }

            return $this->successResponse(
                data: $producto,
                message: 'Producto obtenido exitosamente',
                title: 'Detalle de producto'
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

    public function store(Request $request): JsonResponse
    {
        try {
            // Obtener usuario autenticado
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
                // Datos del producto
                'nombre' => 'required|string|max:300',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|string', // Puede ser URL, base64 o ruta
                'id_subcategoria' => 'nullable|integer|exists:subcategoria,id_subcategoria',
                // Datos del stock (opcionales - si se envían, se crea el stock)
                'precio' => 'nullable|numeric|min:0',
                'cantidad' => 'nullable|integer|min:0',
                'id_unidad' => 'nullable|integer|exists:unidad,id_unidad',
                'tipo_moneda' => 'nullable|string|in:PEN,USD',
                'recibe_ofertas' => 'nullable|boolean',
            ]);

            // Si se envía cantidad, crear producto + stock
            if (isset($validated['cantidad']) && $validated['cantidad'] !== null) {
                // Procesar imagen del producto (base64, URL o ruta)
                $imagenProducto = $validated['imagen'] ?? null;
                if ($imagenProducto) {
                    $imagenProducto = $this->imageService->saveBase64Image($imagenProducto, 'productos');
                }

                // Separar datos de producto y stock
                $productoData = [
                    'nombre' => $validated['nombre'],
                    'descripcion' => $validated['descripcion'] ?? null,
                    'imagen' => $imagenProducto ?? '-',
                    'id_subcategoria' => $validated['id_subcategoria'] ?? null,
                    'estado' => 'ACTIVO',
                ];

                $stockData = [
                    'precio' => $validated['precio'] ?? null,
                    'cantidad' => $validated['cantidad'],
                    'id_unidad' => $validated['id_unidad'] ?? null,
                    'tipo_moneda' => $validated['tipo_moneda'] ?? 'PEN',
                    'recibe_ofertas' => $validated['recibe_ofertas'] ?? false,
                    'imagen' => $imagenProducto ?? null,
                ];

                $resultado = $this->productoService->registrarProductoConStock(
                    $productoData,
                    $stockData,
                    $user->id_usuario
                );

                // Combinar producto y stock en un solo objeto
                $productoArray = $resultado['producto']->toArray();
                $stockArray = $resultado['stock']->toArray();
                
                $dataCombinada = array_merge($productoArray, $stockArray);

                return $this->successResponse(
                    data: $dataCombinada,
                    message: 'Producto y stock registrados exitosamente',
                    code: 201,
                    title: 'Registro exitoso'
                );
            }

            // Si no se envía cantidad, solo crear producto
            // Procesar imagen (base64, URL o ruta)
            $imagen = $validated['imagen'] ?? null;
            if ($imagen) {
                $imagen = $this->imageService->saveBase64Image($imagen, 'productos');
            }

            $validated['imagen'] = $imagen ?? '-';
            $validated['id_usuario'] = $user->id_usuario;
            $validated['estado'] = 'ACTIVO'; // Estado por defecto

            $producto = $this->productoService->create($validated);
            return $this->successResponse(
                data: $producto,
                message: 'Producto creado exitosamente',
                code: 201,
                title: 'Producto creado'
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

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'sometimes|string|max:300',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|string', // Puede ser URL, base64 o ruta
                'id_subcategoria' => 'nullable|integer|exists:subcategoria,id_subcategoria',
            ]);

            // Procesar imagen si se proporciona (base64, URL o ruta)
            if (isset($validated['imagen'])) {
                $imagen = $this->imageService->saveBase64Image($validated['imagen'], 'productos');
                $validated['imagen'] = $imagen ?? '-';
            }

            $updated = $this->productoService->update($id, $validated);
            
            if (!$updated) {
                return $this->notFoundResponse('Producto no encontrado');
            }

            $producto = $this->productoService->getById($id);
            return $this->successResponse(
                data: $producto,
                message: 'Producto actualizado exitosamente',
                title: 'Producto actualizado'
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

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->productoService->delete($id);
            
            if (!$deleted) {
                return $this->notFoundResponse('Producto no encontrado');
            }

            return $this->successResponse(
                data: [],
                message: 'Producto eliminado exitosamente',
                title: 'Producto eliminado'
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
     * Obtiene productos por subcategoría para clientes
     * Solo muestra productos ACTIVOS con stock disponible (sin filtrar por usuario)
     */
    public function bySubcategoria(int $idSubcategoria): JsonResponse
    {
        try {
            // Obtener todos los productos de la subcategoría (sin filtrar por usuario)
            $productos = $this->productoService->findBySubcategoria($idSubcategoria);

            // Obtener información de subcategoría y categoría
            $subcategoria = $this->subcategoriaService->getById($idSubcategoria);
            $categoria = null;
            if ($subcategoria && $subcategoria->getIdCategoria()) {
                $categoria = $this->categoriaService->getById($subcategoria->getIdCategoria());
            }

            // Filtrar y enriquecer productos: solo ACTIVOS con stock disponible
            $productosCompletos = [];
            
            foreach ($productos as $producto) {
                // Filtrar solo productos ACTIVOS
                if ($producto->getEstado() !== 'ACTIVO') {
                    continue;
                }

                // Obtener todos los stocks del producto (de todos los usuarios)
                $stocks = $this->stockService->findByProducto($producto->getId());
                
                // Buscar el primer stock con cantidad disponible > 0
                $stockDisponible = null;
                foreach ($stocks as $stock) {
                    $cantidadVendida = $this->detalleVentaService->getCantidadVendida($stock->getId());
                    $cantidadDisponible = $stock->getCantidad() - $cantidadVendida;
                    
                    // Solo incluir productos con stock disponible > 0
                    if ($cantidadDisponible > 0) {
                        $stockDisponible = $stock;
                        break; // Usar el primer stock disponible encontrado
                    }
                }

                // Si no hay stock disponible, omitir este producto
                if (!$stockDisponible) {
                    continue;
                }

                // Enriquecer producto con información completa
                $productoArray = $producto->toArray();
                
                // Preparar información del stock
                $stockArray = $stockDisponible->toArray();
                $cantidadVendida = $this->detalleVentaService->getCantidadVendida($stockDisponible->getId());
                $cantidadDisponible = $stockDisponible->getCantidad() - $cantidadVendida;
                
                $stockArray['cantidad_disponible'] = $cantidadDisponible;
                $stockArray['cantidad_vendida'] = $cantidadVendida;
                $stockArray['tiene_stock'] = $cantidadDisponible > 0;

                // Obtener información de la unidad
                if ($stockDisponible->getIdUnidad()) {
                    $unidad = $this->unidadService->getById($stockDisponible->getIdUnidad());
                    if ($unidad) {
                        if (is_object($unidad) && method_exists($unidad, 'toArray')) {
                            $stockArray['unidad'] = $unidad->toArray();
                        } elseif (is_array($unidad)) {
                            $stockArray['unidad'] = $unidad;
                        } else {
                            $stockArray['unidad'] = ['id_unidad' => $stockDisponible->getIdUnidad()];
                        }
                    } else {
                        $stockArray['unidad'] = null;
                    }
                } else {
                    $stockArray['unidad'] = null;
                }

                // Calcular estado del stock
                if ($cantidadDisponible > 0 && $cantidadVendida == 0) {
                    $stockArray['estado'] = "ACTIVO";
                } elseif ($cantidadDisponible > 0 && $cantidadVendida > 0) {
                    $stockArray['estado'] = "PARCIALMENTE_VENDIDO";
                } elseif ($cantidadDisponible == 0 && $cantidadVendida > 0) {
                    $stockArray['estado'] = "VENDIDO";
                } else {
                    $stockArray['estado'] = "AGOTADO";
                }

                // Agregar información del stock al producto
                $productoArray['stock'] = $stockArray;
                
                // Calcular estado del producto
                $productoArray['estado'] = $stockArray['estado'];
                $productoArray['activo'] = $cantidadDisponible > 0;
                $productoArray['vendido'] = $cantidadVendida > 0;

                $productosCompletos[] = $productoArray;
            }

            // Preparar respuesta con información completa
            $responseData = [
                'productos' => $productosCompletos,
                'subcategoria' => $subcategoria ? $subcategoria->toArray() : null,
                'categoria' => $categoria ? $categoria->toArray() : null,
            ];

            return $this->successResponse(
                data: $responseData,
                message: 'Productos obtenidos exitosamente',
                title: 'Productos por subcategoría'
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
     * Obtiene productos filtrados por subcategoría y usuario con información completa
     * Si no se envía id_usuario, usa el usuario autenticado del token
     */
    public function bySubcategoriaAndUsuario(Request $request, int $idSubcategoria): JsonResponse
    {
        try {
            // Obtener usuario autenticado
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
                'id_usuario' => 'nullable|integer|exists:usuario,id_usuario',
            ]);

            // Si no se envía id_usuario, usar el del token
            $idUsuario = $validated['id_usuario'] ?? $user->id_usuario;

            $productos = $this->productoService->findBySubcategoriaAndUsuario(
                $idSubcategoria,
                $idUsuario
            );

            // Obtener información de subcategoría
            $subcategoria = $this->subcategoriaService->getById($idSubcategoria);
            $categoria = null;
            if ($subcategoria && $subcategoria->getIdCategoria()) {
                $categoria = $this->categoriaService->getById($subcategoria->getIdCategoria());
            }

            // Enriquecer cada producto con información adicional
            $productosCompletos = array_map(function ($producto) use ($idUsuario) {
                $productoArray = $producto->toArray();
                
                // Obtener stock del producto para este usuario
                $stocks = $this->stockService->findByProducto($producto->getId());
                $stockUsuario = null;
                foreach ($stocks as $stock) {
                    if ($stock->getIdUsuario() == $idUsuario) {
                        $stockUsuario = $stock;
                        break;
                    }
                }
                
                // Agregar información del stock
                if ($stockUsuario) {
                    $stockArray = $stockUsuario->toArray();
                    
                    // Calcular cantidad vendida
                    $cantidadVendida = $this->detalleVentaService->getCantidadVendida($stockUsuario->getId());
                    $cantidadDisponible = $stockUsuario->getCantidad() - $cantidadVendida;
                    
                    // Calcular estado del stock
                    $estadoStock = 'ACTIVO'; // Por defecto
                    if ($cantidadDisponible <= 0) {
                        $estadoStock = 'AGOTADO';
                    } elseif ($cantidadVendida > 0 && $cantidadDisponible > 0) {
                        $estadoStock = 'PARCIALMENTE_VENDIDO';
                    } elseif ($cantidadVendida == $stockUsuario->getCantidad()) {
                        $estadoStock = 'VENDIDO';
                    }
                    
                    // Agregar información de estado y ventas
                    $stockArray['cantidad_disponible'] = max(0, $cantidadDisponible);
                    $stockArray['cantidad_vendida'] = $cantidadVendida;
                    $stockArray['estado'] = $estadoStock;
                    $stockArray['tiene_stock'] = $cantidadDisponible > 0;
                    
                    // Obtener información de la unidad
                    if ($stockUsuario->getIdUnidad()) {
                        $unidad = $this->unidadService->getById($stockUsuario->getIdUnidad());
                        if ($unidad) {
                            // Convertir a array si es un modelo de Eloquent
                            if (is_object($unidad) && method_exists($unidad, 'toArray')) {
                                $stockArray['unidad'] = $unidad->toArray();
                            } elseif (is_array($unidad)) {
                                $stockArray['unidad'] = $unidad;
                            } else {
                                $stockArray['unidad'] = ['id_unidad' => $stockUsuario->getIdUnidad()];
                            }
                        } else {
                            $stockArray['unidad'] = null;
                        }
                    } else {
                        $stockArray['unidad'] = null;
                    }
                    
                    // Calcular estado del producto basado en el stock
                    $productoArray['estado'] = $estadoStock;
                    $productoArray['activo'] = $cantidadDisponible > 0;
                    $productoArray['vendido'] = $cantidadVendida > 0;
                    
                    $productoArray['stock'] = $stockArray;
                } else {
                    $productoArray['stock'] = null;
                    $productoArray['estado'] = 'SIN_STOCK';
                    $productoArray['activo'] = false;
                    $productoArray['vendido'] = false;
                }
                
                return $productoArray;
            }, $productos);

            // Agregar información de subcategoría y categoría
            $responseData = [
                'productos' => $productosCompletos,
                'subcategoria' => $subcategoria ? $subcategoria->toArray() : null,
                'categoria' => $categoria ? $categoria->toArray() : null,
            ];

            return $this->successResponse(
                data: $responseData,
                message: 'Productos obtenidos exitosamente',
                title: 'Productos por subcategoría y usuario'
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
     * Registra un producto con su stock asociado al usuario autenticado
     */
    public function registro(Request $request): JsonResponse
    {
        try {
            // Obtener usuario autenticado
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
                // Datos del producto
                'nombre' => 'required|string|max:300',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|string', // Puede ser URL, base64 o ruta
                'id_subcategoria' => 'nullable|integer|exists:subcategoria,id_subcategoria',
                // Datos del stock
                'precio' => 'nullable|numeric|min:0',
                'cantidad' => 'required|integer|min:0',
                'id_unidad' => 'nullable|integer|exists:unidad,id_unidad',
                'tipo_moneda' => 'nullable|string|in:PEN,USD',
                'recibe_ofertas' => 'nullable|boolean',
                'imagen_stock' => 'nullable|string', // Imagen específica para el stock (opcional)
            ]);

            // Procesar imagen del producto (base64, URL o ruta)
            $imagenProducto = $validated['imagen'] ?? null;
            if ($imagenProducto) {
                $imagenProducto = $this->imageService->saveBase64Image($imagenProducto, 'productos');
            }

            // Procesar imagen del stock (si se proporciona una específica, sino usar la del producto)
            $imagenStock = $validated['imagen_stock'] ?? $imagenProducto;
            if ($imagenStock) {
                $imagenStock = $this->imageService->saveBase64Image($imagenStock, 'stocks');
            } else {
                // Si no hay imagen, usar valor por defecto
                $imagenStock = null;
            }

            // Separar datos de producto y stock
            $productoData = [
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
                'imagen' => $imagenProducto ?? '-',
                'id_subcategoria' => $validated['id_subcategoria'] ?? null,
            ];

            $stockData = [
                'precio' => $validated['precio'] ?? null,
                'cantidad' => $validated['cantidad'],
                'id_unidad' => $validated['id_unidad'] ?? null,
                'tipo_moneda' => $validated['tipo_moneda'] ?? 'PEN',
                'recibe_ofertas' => $validated['recibe_ofertas'] ?? false,
                'imagen' => $imagenStock ?? null,
            ];

            $resultado = $this->productoService->registrarProductoConStock(
                $productoData,
                $stockData,
                $user->id_usuario
            );

            // Combinar producto y stock en un solo objeto
            $productoArray = $resultado['producto']->toArray();
            $stockArray = $resultado['stock']->toArray();
            
            $dataCombinada = array_merge($productoArray, $stockArray);

            return $this->successResponse(
                data: $dataCombinada,
                message: 'Producto y stock registrados exitosamente',
                code: 201,
                title: 'Registro exitoso'
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
     * Cambia el estado de un producto (ACTIVO/INACTIVO)
     * Solo el dueño del producto puede cambiar su estado
     */
    public function cambiarEstado(Request $request, int $id): JsonResponse
    {
        try {
            // Obtener usuario autenticado
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
                'estado' => 'required|string|in:ACTIVO,INACTIVO',
            ]);

            // Obtener el producto
            $producto = $this->productoService->getById($id);
            
            if (!$producto) {
                return $this->notFoundResponse('Producto no encontrado');
            }

            // Verificar que el usuario sea el dueño del producto
            if ($producto->getIdUsuario() != $user->id_usuario) {
                return $this->errorResponse(
                    message: 'No tienes permiso para modificar este producto',
                    code: 403,
                    codeError: '403',
                    title: 'Acceso denegado'
                );
            }

            // Actualizar el estado
            $updated = $this->productoService->update($id, ['estado' => $validated['estado']]);
            
            if (!$updated) {
                return $this->errorResponse(
                    message: 'Error al actualizar el estado del producto',
                    code: 500,
                    codeError: '500',
                    title: 'Error del servidor'
                );
            }

            // Obtener el producto actualizado
            $productoActualizado = $this->productoService->getById($id);

            return $this->successResponse(
                data: $productoActualizado,
                message: 'Estado del producto actualizado exitosamente',
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

