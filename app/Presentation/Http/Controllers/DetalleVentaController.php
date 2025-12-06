<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\DetalleVentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DetalleVentaController extends BaseController
{
    public function __construct(
        private DetalleVentaService $detalleVentaService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $detalles = $this->detalleVentaService->getAll();
            return $this->successResponse(data: $detalles, message: 'Detalles de venta obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $detalle = $this->detalleVentaService->getById($id);
            
            if (!$detalle) {
                return $this->notFoundResponse('Detalle de venta no encontrado');
            }

            return $this->successResponse(data: $detalle, message: 'Detalle de venta obtenido exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'cantidad' => 'required|integer|min:1',
                'id_stock' => 'required|integer|exists:stock,id_stock',
                'id_venta' => 'required|integer|exists:venta,id_venta',
            ]);

            $detalle = $this->detalleVentaService->create($validated);
            return $this->successResponse(data: $detalle, message: 'Detalle de venta creado exitosamente', code: 201, title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function byVenta(int $idVenta): JsonResponse
    {
        try {
            $detalles = $this->detalleVentaService->findByVenta($idVenta);
            return $this->successResponse(data: $detalles, message: 'Detalles de venta obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }
}

