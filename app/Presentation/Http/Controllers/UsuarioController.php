<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioController extends BaseController
{
    public function __construct(
        private UsuarioService $usuarioService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $usuarios = $this->usuarioService->getAll();
            return $this->successResponse(
                data: $usuarios,
                message: 'Usuarios obtenidos exitosamente',
                title: 'Lista de usuarios'
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
            $usuario = $this->usuarioService->getById($id);
            
            if (!$usuario) {
                return $this->notFoundResponse('Usuario no encontrado');
            }

            return $this->successResponse(
                data: $usuario,
                message: 'Usuario obtenido exitosamente',
                title: 'Detalle de usuario'
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
                'documento' => 'required|string|max:11',
                'nombre' => 'nullable|string|max:300',
                'correo' => 'nullable|email|max:300',
                'clave' => 'nullable|string|max:16',
                'estado' => 'nullable|string|size:1',
                'tipo_vendedor' => 'required|string|max:3',
                'tipo_persona' => 'nullable|string|size:1',
            ]);

            $usuario = $this->usuarioService->create($validated);
            return $this->successResponse(
                data: $usuario,
                message: 'Usuario creado exitosamente',
                code: 201,
                title: 'Usuario creado'
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

