<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\PersonaNaturalService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonaNaturalController extends BaseController
{
    public function __construct(
        private PersonaNaturalService $personaNaturalService,
        private UsuarioService $usuarioService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $personas = $this->personaNaturalService->getAll();
            return $this->successResponse(data: $personas, message: 'Personas naturales obtenidas exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function show(string $dni): JsonResponse
    {
        try {
            $persona = $this->personaNaturalService->findByDni($dni);
            
            if (!$persona) {
                return $this->notFoundResponse('Persona natural no encontrada');
            }

            return $this->successResponse(data: $persona, message: 'Persona natural obtenida exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'dni' => 'required|string|size:8',
                'nombres' => 'nullable|string|max:300',
                'apellidos' => 'nullable|string|max:300',
                'direccion' => 'nullable|string|max:500',
                'celular' => 'nullable|string|max:15',
                'pais' => 'nullable|string|max:300',
                'departamento' => 'nullable|integer',
                'provincia' => 'nullable|integer',
                'distrito' => 'nullable|integer',
            ]);

            $persona = $this->personaNaturalService->create($validated);
            return $this->successResponse(data: $persona, message: 'Persona natural creada exitosamente', code: 201, title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function registro(Request $request): JsonResponse
    {
        try {
            // Preparar datos para validación (convertir correo vacío a null)
            $requestData = $request->all();
            if (isset($requestData['correo']) && $requestData['correo'] === '') {
                $requestData['correo'] = null;
            }
            
            $validated = validator($requestData, [
                // Datos de persona natural
                'dni' => 'required|string|size:8',
                'nombres' => 'nullable|string|max:300',
                'apellidos' => 'nullable|string|max:300',
                'direccion' => 'nullable|string|max:500',
                'celular' => 'nullable|string|max:15',
                'pais' => 'nullable|string|max:300',
                'departamento' => 'nullable|integer',
                'provincia' => 'nullable|integer',
                'distrito' => 'nullable|integer',
                // Datos de usuario
                'usuario' => 'required|string|max:100|unique:usuario,username',
                'correo' => 'nullable|email|max:300|unique:usuario,correo',
                'clave' => 'required|string|min:6|max:16',
                'nombre' => 'nullable|string|max:300',
                'tipo_vendedor' => 'nullable|string|max:3',
            ])->validate();

            // Verificar si la persona natural ya existe
            $personaExistente = $this->personaNaturalService->findByDni($validated['dni']);
            if ($personaExistente && $personaExistente->getIdUsuario()) {
                return $this->errorResponse(
                    message: 'Esta persona natural ya tiene un usuario asociado',
                    code: 409,
                    codeError: '409',
                    title: 'Conflicto'
                );
            }

            // Crear usuario
            $usuarioData = [
                'documento' => $validated['dni'],
                'username' => $validated['usuario'],
                'correo' => $validated['correo'],
                'clave' => $validated['clave'],
                'nombre' => $validated['nombre'] ?? ($validated['nombres'] . ' ' . $validated['apellidos'] ?? ''),
                'estado' => 'D', // Desactivado por defecto
                'tipo_vendedor' => $validated['tipo_vendedor'] ?? 'NAT',
                'tipo_persona' => 'N', // Natural
            ];

            $usuario = $this->usuarioService->create($usuarioData);

            // Crear o actualizar persona natural
            $personaData = [
                'dni' => $validated['dni'],
                'nombres' => $validated['nombres'] ?? null,
                'apellidos' => $validated['apellidos'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'celular' => $validated['celular'] ?? null,
                'pais' => $validated['pais'] ?? null,
                'departamento' => $validated['departamento'] ?? null,
                'provincia' => $validated['provincia'] ?? null,
                'distrito' => $validated['distrito'] ?? null,
                'id_usuario' => $usuario->getId(),
            ];

            if ($personaExistente) {
                // Actualizar persona existente con el usuario
                $this->personaNaturalService->enlazarUsuario($validated['dni'], $usuario->getId());
                $persona = $this->personaNaturalService->findByDni($validated['dni']);
            } else {
                // Crear nueva persona natural
                $persona = $this->personaNaturalService->create($personaData);
            }

            return $this->successResponse(
                data: [
                    'persona_natural' => $persona,
                    'usuario' => $usuario,
                ],
                message: 'Registro completado exitosamente',
                code: 201,
                title: 'Registro exitoso'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function update(Request $request, string $dni): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombres' => 'nullable|string|max:300',
                'apellidos' => 'nullable|string|max:300',
                'direccion' => 'nullable|string|max:500',
                'celular' => 'nullable|string|max:15',
                'pais' => 'nullable|string|max:300',
                'departamento' => 'nullable|integer',
                'provincia' => 'nullable|integer',
                'distrito' => 'nullable|integer',
            ]);

            $persona = $this->personaNaturalService->update($dni, $validated);
            
            if (!$persona) {
                return $this->notFoundResponse('Persona natural no encontrada');
            }

            return $this->successResponse(data: $persona, message: 'Persona natural actualizada exitosamente', title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function destroy(string $dni): JsonResponse
    {
        try {
            $eliminado = $this->personaNaturalService->delete($dni);
            
            if (!$eliminado) {
                return $this->notFoundResponse('Persona natural no encontrada');
            }

            return $this->successResponse(data: [], message: 'Persona natural eliminada exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function enlazarUsuario(Request $request, string $dni): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_usuario' => 'required|integer|exists:usuario,id_usuario',
            ]);

            $enlazado = $this->personaNaturalService->enlazarUsuario($dni, $validated['id_usuario']);
            
            if (!$enlazado) {
                return $this->notFoundResponse('Persona natural no encontrada');
            }

            $persona = $this->personaNaturalService->findByDni($dni);
            return $this->successResponse(data: $persona, message: 'Usuario enlazado exitosamente', title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }
}

