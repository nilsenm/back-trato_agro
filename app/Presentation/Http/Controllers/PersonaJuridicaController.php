<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\PersonaJuridicaService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonaJuridicaController extends BaseController
{
    public function __construct(
        private PersonaJuridicaService $personaJuridicaService,
        private UsuarioService $usuarioService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $personas = $this->personaJuridicaService->getAll();
            return $this->successResponse(data: $personas, message: 'Personas jurídicas obtenidas exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function show(string $ruc): JsonResponse
    {
        try {
            $persona = $this->personaJuridicaService->findByRuc($ruc);
            
            if (!$persona) {
                return $this->notFoundResponse('Persona jurídica no encontrada');
            }

            return $this->successResponse(data: $persona, message: 'Persona jurídica obtenida exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'ruc' => 'required|string|size:11',
                'razon_social' => 'nullable|string|max:300',
                'domicilio_fiscal' => 'nullable|string|max:500',
                'nombre_representante_legal' => 'nullable|string|max:300',
                'celular' => 'nullable|string|max:15',
                'pais' => 'nullable|string|max:300',
                'departamento' => 'nullable|integer',
                'provincia' => 'nullable|integer',
                'distrito' => 'nullable|integer',
            ]);

            $persona = $this->personaJuridicaService->create($validated);
            return $this->successResponse(data: $persona, message: 'Persona jurídica creada exitosamente', code: 201, title: 'Operación exitosa');
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
                // Datos de persona jurídica
                'ruc' => 'required|string|size:11',
                'razon_social' => 'nullable|string|max:300',
                'domicilio_fiscal' => 'nullable|string|max:500',
                'nombre_representante_legal' => 'nullable|string|max:300',
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

            // Verificar si la persona jurídica ya existe
            $personaExistente = $this->personaJuridicaService->findByRuc($validated['ruc']);
            if ($personaExistente && $personaExistente->getIdUsuario()) {
                return $this->errorResponse(
                    message: 'Esta persona jurídica ya tiene un usuario asociado',
                    code: 409,
                    codeError: '409',
                    title: 'Conflicto'
                );
            }

            // Crear usuario
            $usuarioData = [
                'documento' => $validated['ruc'],
                'username' => $validated['usuario'],
                'correo' => $validated['correo'],
                'clave' => $validated['clave'],
                'nombre' => $validated['nombre'] ?? $validated['razon_social'] ?? '',
                'estado' => 'D', // Desactivado por defecto
                'tipo_vendedor' => $validated['tipo_vendedor'] ?? 'JUR',
                'tipo_persona' => 'J', // Jurídica
            ];

            $usuario = $this->usuarioService->create($usuarioData);

            // Crear o actualizar persona jurídica
            $personaData = [
                'ruc' => $validated['ruc'],
                'razon_social' => $validated['razon_social'] ?? null,
                'domicilio_fiscal' => $validated['domicilio_fiscal'] ?? null,
                'nombre_representante_legal' => $validated['nombre_representante_legal'] ?? null,
                'celular' => $validated['celular'] ?? null,
                'pais' => $validated['pais'] ?? null,
                'departamento' => $validated['departamento'] ?? null,
                'provincia' => $validated['provincia'] ?? null,
                'distrito' => $validated['distrito'] ?? null,
                'id_usuario' => $usuario->getId(),
            ];

            if ($personaExistente) {
                // Actualizar persona existente con el usuario
                $this->personaJuridicaService->enlazarUsuario($validated['ruc'], $usuario->getId());
                $persona = $this->personaJuridicaService->findByRuc($validated['ruc']);
            } else {
                // Crear nueva persona jurídica
                $persona = $this->personaJuridicaService->create($personaData);
            }

            return $this->successResponse(
                data: [
                    'persona_juridica' => $persona,
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

    public function update(Request $request, string $ruc): JsonResponse
    {
        try {
            $validated = $request->validate([
                'razon_social' => 'nullable|string|max:300',
                'domicilio_fiscal' => 'nullable|string|max:500',
                'nombre_representante_legal' => 'nullable|string|max:300',
                'celular' => 'nullable|string|max:15',
                'pais' => 'nullable|string|max:300',
                'departamento' => 'nullable|integer',
                'provincia' => 'nullable|integer',
                'distrito' => 'nullable|integer',
            ]);

            $persona = $this->personaJuridicaService->updateAndGet($ruc, $validated);
            
            if (!$persona) {
                return $this->notFoundResponse('Persona jurídica no encontrada');
            }

            return $this->successResponse(data: $persona, message: 'Persona jurídica actualizada exitosamente', title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function destroy(string $ruc): JsonResponse
    {
        try {
            $eliminado = $this->personaJuridicaService->delete($ruc);
            
            if (!$eliminado) {
                return $this->notFoundResponse('Persona jurídica no encontrada');
            }

            return $this->successResponse(data: [], message: 'Persona jurídica eliminada exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function enlazarUsuario(Request $request, string $ruc): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_usuario' => 'required|integer|exists:usuario,id_usuario',
            ]);

            $enlazado = $this->personaJuridicaService->enlazarUsuario($ruc, $validated['id_usuario']);
            
            if (!$enlazado) {
                return $this->notFoundResponse('Persona jurídica no encontrada');
            }

            $persona = $this->personaJuridicaService->findByRuc($ruc);
            return $this->successResponse(data: $persona, message: 'Usuario enlazado exitosamente', title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }
}



