<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\SubcategoriaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubcategoriaController extends BaseController
{
    public function __construct(
        private SubcategoriaService $subcategoriaService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $subcategorias = $this->subcategoriaService->getAll();
            return $this->successResponse(data: $subcategorias, message: 'Subcategorías obtenidas exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $subcategoria = $this->subcategoriaService->getById($id);
            
            if (!$subcategoria) {
                return $this->notFoundResponse('Subcategoría no encontrada');
            }

            return $this->successResponse(data: $subcategoria, message: 'Subcategoría obtenida exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function byCategoria(int $idCategoria): JsonResponse
    {
        try {
            $subcategorias = $this->subcategoriaService->findByCategoria($idCategoria);
            return $this->successResponse(data: $subcategorias, message: 'Subcategorías obtenidas exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:300',
                'id_categoria' => 'required|integer|exists:categoria,id_categoria',
            ]);

            $subcategoria = $this->subcategoriaService->create($validated);
            return $this->successResponse(data: $subcategoria, message: 'Subcategoría creada exitosamente', code: 201, title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }
}









