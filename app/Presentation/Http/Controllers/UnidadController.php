<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\UnidadService;
use Illuminate\Http\JsonResponse;

class UnidadController extends BaseController
{
    public function __construct(
        private UnidadService $unidadService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $unidades = $this->unidadService->getAll();
            return $this->successResponse(data: $unidades, message: 'Unidades obtenidas exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $unidad = $this->unidadService->getById($id);
            
            if (!$unidad) {
                return $this->notFoundResponse('Unidad no encontrada');
            }

            return $this->successResponse(data: $unidad, message: 'Unidad obtenida exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }
}









