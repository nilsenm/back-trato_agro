<?php

use App\Presentation\Http\Controllers\AuthController;
use App\Presentation\Http\Controllers\CategoriaController;
use App\Presentation\Http\Controllers\ProductoController;
use App\Presentation\Http\Controllers\StockController;
use App\Presentation\Http\Controllers\UnidadController;
use App\Presentation\Http\Controllers\SubcategoriaController;
use App\Presentation\Http\Controllers\UbicacionController;
use App\Presentation\Http\Controllers\UsuarioController;
use App\Presentation\Http\Controllers\PersonaNaturalController;
use App\Presentation\Http\Controllers\PersonaJuridicaController;
use App\Presentation\Http\Controllers\VentaController;
use App\Presentation\Http\Controllers\DetalleVentaController;
use App\Presentation\Http\Controllers\CarritoController;
use App\Presentation\Http\Controllers\ConsultaExternaController;
use App\Presentation\Http\Controllers\ReporteController;
use App\Presentation\Http\Controllers\OfertaController;
use App\Presentation\Http\Controllers\MensajeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'TratoAgro API is running'
    ]);
});

// Autenticación JWT
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/refresh', [AuthController::class, 'refresh']);

// Rutas protegidas con JWT
Route::middleware(['jwt.auth'])->group(function () {
    Route::post('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

// Categorías (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::apiResource('categorias', CategoriaController::class);
});

// Productos destacados (público - sin autenticación) - DEBE IR ANTES del apiResource
Route::get('productos/destacados', [StockController::class, 'destacados']);

// Productos (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::apiResource('productos', ProductoController::class);
    Route::post('productos/registro', [ProductoController::class, 'registro']); // Registro completo con stock
    Route::get('productos/subcategoria/{idSubcategoria}', [ProductoController::class, 'bySubcategoria']);
    Route::get('productos/subcategoria/{idSubcategoria}/usuario', [ProductoController::class, 'bySubcategoriaAndUsuario']); // Por subcategoría y usuario
    Route::get('productos/listado', [ProductoController::class, 'listado']); // Listado con paginación y filtros
    Route::get('productos/todos', [ProductoController::class, 'todos']); // Todos los productos sin paginación
    Route::put('productos/{id}/estado', [ProductoController::class, 'cambiarEstado']); // Cambiar estado (ACTIVO/INACTIVO)
});

// Carrito (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('carrito', [CarritoController::class, 'index']); // Obtener carrito completo
    Route::post('carrito', [CarritoController::class, 'store']); // Agregar item al carrito
    Route::put('carrito/{id}', [CarritoController::class, 'update']); // Actualizar cantidad
    Route::delete('carrito/{id}', [CarritoController::class, 'destroy']); // Eliminar item
    Route::post('carrito/limpiar', [CarritoController::class, 'limpiar']); // Limpiar todo el carrito
    Route::post('carrito/finalizar-venta', [CarritoController::class, 'finalizarVenta']); // Finalizar venta desde el carrito
});

// Stocks (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::apiResource('stocks', StockController::class);
    Route::get('stocks/usuario/{idUsuario}', [StockController::class, 'byUsuario']);
    Route::get('stocks/producto/{idProducto}', [StockController::class, 'byProducto']);
    Route::get('stocks/disponibles', [StockController::class, 'conStock']);
});

// Unidades (público)
Route::get('unidades', [UnidadController::class, 'index']);
Route::get('unidades/{id}', [UnidadController::class, 'show']);

// Subcategorías (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('subcategorias', [SubcategoriaController::class, 'index']);
    Route::get('subcategorias/{id}', [SubcategoriaController::class, 'show']);
    Route::post('subcategorias', [SubcategoriaController::class, 'store']);
    Route::get('subcategorias/categoria/{idCategoria}', [SubcategoriaController::class, 'byCategoria']);
});

// Ubicaciones (público)
Route::get('ubicaciones/departamentos', [UbicacionController::class, 'departamentos']);
Route::get('ubicaciones/provincias/{idDepartamento}', [UbicacionController::class, 'provincias']);
Route::get('ubicaciones/distritos/{idProvincia}', [UbicacionController::class, 'distritos']);

// Usuarios (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::apiResource('usuarios', UsuarioController::class);
});

// Personas Naturales
Route::post('personas-naturales/registro', [PersonaNaturalController::class, 'registro']); // Registro público
Route::post('personas-naturales', [PersonaNaturalController::class, 'store']); // Registro simple público

// Personas Naturales (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('personas-naturales', [PersonaNaturalController::class, 'index']);
    Route::get('personas-naturales/{dni}', [PersonaNaturalController::class, 'show']);
    Route::put('personas-naturales/{dni}', [PersonaNaturalController::class, 'update']);
    Route::delete('personas-naturales/{dni}', [PersonaNaturalController::class, 'destroy']);
    Route::post('personas-naturales/{dni}/enlazar-usuario', [PersonaNaturalController::class, 'enlazarUsuario']);
});

// Personas Jurídicas
Route::post('personas-juridicas/registro', [PersonaJuridicaController::class, 'registro']); // Registro público
Route::post('personas-juridicas', [PersonaJuridicaController::class, 'store']); // Registro simple público

// Personas Jurídicas (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('personas-juridicas', [PersonaJuridicaController::class, 'index']);
    Route::get('personas-juridicas/{ruc}', [PersonaJuridicaController::class, 'show']);
    Route::put('personas-juridicas/{ruc}', [PersonaJuridicaController::class, 'update']);
    Route::delete('personas-juridicas/{ruc}', [PersonaJuridicaController::class, 'destroy']);
    Route::post('personas-juridicas/{ruc}/enlazar-usuario', [PersonaJuridicaController::class, 'enlazarUsuario']);
});

// Ventas (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    // IMPORTANTE: Las rutas específicas DEBEN ir ANTES de apiResource
    // porque apiResource crea GET /ventas/{venta} que captura cualquier ruta
    Route::get('ventas/mis-compras', [VentaController::class, 'misCompras']); // Mis compras detalladas
    Route::get('ventas/mis-ventas', [VentaController::class, 'misVentas']); // Mis ventas como vendedor
    Route::put('ventas/{id}/estado', [VentaController::class, 'cambiarEstado']); // Cambiar estado de venta
    Route::get('ventas/usuario/{idUsuario}', [VentaController::class, 'byUsuario']);
    Route::get('ventas/usuario/{idUsuario}/ultima', [VentaController::class, 'ultimaVenta']);
    // apiResource debe ir al final para no capturar las rutas específicas
    Route::apiResource('ventas', VentaController::class);
});

// Detalles de Venta (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::apiResource('detalles-venta', DetalleVentaController::class);
    Route::get('detalles-venta/venta/{idVenta}', [DetalleVentaController::class, 'byVenta']);
});


Route::post('consultas/dni', [ConsultaExternaController::class, 'consultarDni']);
Route::post('consultas/ruc', [ConsultaExternaController::class, 'consultarRuc']);


// Reportes (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('reportes/ventas/categoria/{idCategoria}', [ReporteController::class, 'ventasPorCategoria']);
});

// Ofertas (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::post('ofertas', [OfertaController::class, 'store']); // Crear oferta
    // Rutas específicas DEBEN ir antes de la ruta genérica {id}
    Route::get('ofertas/stock/{idStock}', [OfertaController::class, 'byStock']); // Ofertas de un producto
    Route::get('ofertas/mis-ofertas', [OfertaController::class, 'misOfertas']); // Mis ofertas enviadas
    Route::get('ofertas/recibidas', [OfertaController::class, 'ofertasRecibidas']); // Ofertas recibidas
    Route::get('ofertas/{id}', [OfertaController::class, 'show']); // Ver oferta (genérica - debe ir al final)
    Route::post('ofertas/{id}/aceptar', [OfertaController::class, 'aceptar']); // Aceptar oferta
    Route::post('ofertas/{id}/rechazar', [OfertaController::class, 'rechazar']); // Rechazar oferta
    Route::post('ofertas/{id}/cancelar', [OfertaController::class, 'cancelar']); // Cancelar oferta
});

// Mensajes (protegido con JWT)
Route::middleware(['jwt.auth'])->group(function () {
    Route::post('mensajes', [MensajeController::class, 'store']); // Enviar mensaje
    Route::get('mensajes/{id}', [MensajeController::class, 'show']); // Ver mensaje
    Route::get('mensajes/oferta/{idOferta}', [MensajeController::class, 'byOferta']); // Mensajes de una oferta
    Route::get('mensajes/conversacion/{idUsuario}', [MensajeController::class, 'conversacion']); // Conversación con usuario
    Route::get('mensajes/enviados', [MensajeController::class, 'mensajesEnviados']); // Mensajes enviados
    Route::get('mensajes/recibidos', [MensajeController::class, 'mensajesRecibidos']); // Mensajes recibidos
    Route::post('mensajes/{id}/leido', [MensajeController::class, 'marcarLeido']); // Marcar como leído
    Route::get('mensajes/no-leidos/cantidad', [MensajeController::class, 'contarNoLeidos']); // Contar no leídos
});

