<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'usuario' => 'required|string', // Puede ser usuario o correo
                'clave' => 'required|string',
            ]);

            $result = $this->authService->login($validated['usuario'], $validated['clave']);
            
            if (!$result['success']) {
                return $this->errorResponse(
                    message: $result['message'],
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            return $this->successResponse(
                data: $result,
                message: 'Login exitoso',
                title: 'Autenticación exitosa'
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

    public function me(): JsonResponse
    {
        try {
            $usuario = $this->authService->me();
            
            if (!$usuario) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            return $this->successResponse(
                data: $usuario,
                message: 'Usuario autenticado',
                title: 'Información del usuario'
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

    public function logout(): JsonResponse
    {
        try {
            $logout = $this->authService->logout();
            
            if (!$logout) {
                return $this->errorResponse(
                    message: 'Error al cerrar sesión',
                    code: 500,
                    codeError: '500',
                    title: 'Error del servidor'
                );
            }

            return $this->successResponse(
                data: [],
                message: 'Sesión cerrada exitosamente',
                title: 'Logout exitoso'
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

    public function refresh(): JsonResponse
    {
        try {
            $result = $this->authService->refresh();
            
            if (!$result['success']) {
                return $this->errorResponse(
                    message: $result['message'],
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            return $this->successResponse(
                data: $result,
                message: 'Token refrescado exitosamente',
                title: 'Token actualizado'
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

