<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\VentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VentaController extends BaseController
{
    public function __construct(
        private VentaService $ventaService
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
}

