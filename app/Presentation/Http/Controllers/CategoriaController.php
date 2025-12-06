<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\CategoriaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends BaseController
{
    public function __construct(
        private CategoriaService $categoriaService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $categorias = $this->categoriaService->getAll();
            return $this->successResponse(
                data: $categorias,
                message: 'Categorías obtenidas exitosamente',
                title: 'Lista de categorías'
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

    public function show(int $id): JsonResponse
    {
        try {
            $categoria = $this->categoriaService->getById($id);
            
            if (!$categoria) {
                return $this->notFoundResponse('Categoría no encontrada');
            }

            return $this->successResponse(
                data: $categoria,
                message: 'Categoría obtenida exitosamente',
                title: 'Detalle de categoría'
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
            $validated = $request->validate([
                'nombre' => 'required|string|max:300',
                'icono' => 'nullable|string|max:500',
            ]);

            $categoria = $this->categoriaService->create($validated);
            return $this->successResponse(
                data: $categoria,
                message: 'Categoría creada exitosamente',
                code: 201,
                title: 'Categoría creada'
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
                'icono' => 'nullable|string|max:500',
            ]);

            $updated = $this->categoriaService->update($id, $validated);
            
            if (!$updated) {
                return $this->notFoundResponse('Categoría no encontrada');
            }

            $categoria = $this->categoriaService->getById($id);
            return $this->successResponse(
                data: $categoria,
                message: 'Categoría actualizada exitosamente',
                title: 'Categoría actualizada'
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
            $deleted = $this->categoriaService->delete($id);
            
            if (!$deleted) {
                return $this->notFoundResponse('Categoría no encontrada');
            }

            return $this->successResponse(
                data: [],
                message: 'Categoría eliminada exitosamente',
                title: 'Categoría eliminada'
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

