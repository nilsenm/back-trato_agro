<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\MensajeService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MensajeController extends BaseController
{
    public function __construct(
        private MensajeService $mensajeService,
        private UsuarioService $usuarioService
    ) {}

    /**
     * Enviar un mensaje
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $validated = $request->validate([
                'id_oferta' => 'nullable|integer|exists:ofertas,id_oferta',
                'id_usuario_destinatario' => 'required|integer|exists:usuario,id_usuario',
                'mensaje' => 'required|string|max:2000',
            ]);

            $mensaje = $this->mensajeService->enviarMensaje([
                'id_oferta' => $validated['id_oferta'] ?? null,
                'id_usuario_remitente' => $user->id_usuario,
                'id_usuario_destinatario' => $validated['id_usuario_destinatario'],
                'mensaje' => $validated['mensaje'],
            ]);

            return $this->successResponse(
                data: $mensaje,
                message: 'Mensaje enviado exitosamente',
                code: 201,
                title: 'Mensaje enviado'
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

    /**
     * Obtener mensajes de una oferta
     */
    public function byOferta(int $idOferta): JsonResponse
    {
        try {
            $mensajes = $this->mensajeService->findByOferta($idOferta);
            
            // Enriquecer cada mensaje con información de los usuarios
            $mensajesEnriquecidos = [];
            foreach ($mensajes as $mensaje) {
                $mensajeArray = $mensaje->toArray();
                
                // Obtener información del remitente
                if ($mensaje->getIdUsuarioRemitente()) {
                    $remitente = $this->usuarioService->getById($mensaje->getIdUsuarioRemitente());
                    if ($remitente) {
                        $mensajeArray['remitente'] = [
                            'id_usuario' => $remitente->getId(),
                            'nombre' => $remitente->getNombre(),
                            'username' => $remitente->getUsername(),
                            'correo' => $remitente->getCorreo(),
                            'documento' => $remitente->getDocumento(),
                        ];
                    }
                }
                
                // Obtener información del destinatario
                if ($mensaje->getIdUsuarioDestinatario()) {
                    $destinatario = $this->usuarioService->getById($mensaje->getIdUsuarioDestinatario());
                    if ($destinatario) {
                        $mensajeArray['destinatario'] = [
                            'id_usuario' => $destinatario->getId(),
                            'nombre' => $destinatario->getNombre(),
                            'username' => $destinatario->getUsername(),
                            'correo' => $destinatario->getCorreo(),
                            'documento' => $destinatario->getDocumento(),
                        ];
                    }
                }
                
                $mensajesEnriquecidos[] = $mensajeArray;
            }
            
            return $this->successResponse(
                data: $mensajesEnriquecidos,
                message: 'Mensajes obtenidos exitosamente',
                title: 'Mensajes de la oferta'
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

    /**
     * Obtener conversación entre dos usuarios
     */
    public function conversacion(int $idUsuario): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $mensajes = $this->mensajeService->findByUsuarios($user->id_usuario, $idUsuario);
            
            // Enriquecer cada mensaje con información de los usuarios
            $mensajesEnriquecidos = [];
            foreach ($mensajes as $mensaje) {
                $mensajeArray = $mensaje->toArray();
                
                // Obtener información del remitente
                if ($mensaje->getIdUsuarioRemitente()) {
                    $remitente = $this->usuarioService->getById($mensaje->getIdUsuarioRemitente());
                    if ($remitente) {
                        $mensajeArray['remitente'] = [
                            'id_usuario' => $remitente->getId(),
                            'nombre' => $remitente->getNombre(),
                            'username' => $remitente->getUsername(),
                            'correo' => $remitente->getCorreo(),
                            'documento' => $remitente->getDocumento(),
                        ];
                    }
                }
                
                // Obtener información del destinatario
                if ($mensaje->getIdUsuarioDestinatario()) {
                    $destinatario = $this->usuarioService->getById($mensaje->getIdUsuarioDestinatario());
                    if ($destinatario) {
                        $mensajeArray['destinatario'] = [
                            'id_usuario' => $destinatario->getId(),
                            'nombre' => $destinatario->getNombre(),
                            'username' => $destinatario->getUsername(),
                            'correo' => $destinatario->getCorreo(),
                            'documento' => $destinatario->getDocumento(),
                        ];
                    }
                }
                
                $mensajesEnriquecidos[] = $mensajeArray;
            }
            
            return $this->successResponse(
                data: $mensajesEnriquecidos,
                message: 'Conversación obtenida exitosamente',
                title: 'Conversación'
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

    /**
     * Obtener mensajes enviados por el usuario autenticado
     */
    public function mensajesEnviados(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $mensajes = $this->mensajeService->findByUsuarioRemitente($user->id_usuario);
            
            // Enriquecer cada mensaje con información de los usuarios
            $mensajesEnriquecidos = [];
            foreach ($mensajes as $mensaje) {
                $mensajeArray = $mensaje->toArray();
                
                // Obtener información del remitente
                if ($mensaje->getIdUsuarioRemitente()) {
                    $remitente = $this->usuarioService->getById($mensaje->getIdUsuarioRemitente());
                    if ($remitente) {
                        $mensajeArray['remitente'] = [
                            'id_usuario' => $remitente->getId(),
                            'nombre' => $remitente->getNombre(),
                            'username' => $remitente->getUsername(),
                            'correo' => $remitente->getCorreo(),
                            'documento' => $remitente->getDocumento(),
                        ];
                    }
                }
                
                // Obtener información del destinatario
                if ($mensaje->getIdUsuarioDestinatario()) {
                    $destinatario = $this->usuarioService->getById($mensaje->getIdUsuarioDestinatario());
                    if ($destinatario) {
                        $mensajeArray['destinatario'] = [
                            'id_usuario' => $destinatario->getId(),
                            'nombre' => $destinatario->getNombre(),
                            'username' => $destinatario->getUsername(),
                            'correo' => $destinatario->getCorreo(),
                            'documento' => $destinatario->getDocumento(),
                        ];
                    }
                }
                
                $mensajesEnriquecidos[] = $mensajeArray;
            }
            
            return $this->successResponse(
                data: $mensajesEnriquecidos,
                message: 'Mensajes enviados obtenidos exitosamente',
                title: 'Mensajes enviados'
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

    /**
     * Obtener mensajes recibidos por el usuario autenticado
     */
    public function mensajesRecibidos(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $mensajes = $this->mensajeService->findByUsuarioDestinatario($user->id_usuario);
            
            // Enriquecer cada mensaje con información de los usuarios
            $mensajesEnriquecidos = [];
            foreach ($mensajes as $mensaje) {
                $mensajeArray = $mensaje->toArray();
                
                // Obtener información del remitente
                if ($mensaje->getIdUsuarioRemitente()) {
                    $remitente = $this->usuarioService->getById($mensaje->getIdUsuarioRemitente());
                    if ($remitente) {
                        $mensajeArray['remitente'] = [
                            'id_usuario' => $remitente->getId(),
                            'nombre' => $remitente->getNombre(),
                            'username' => $remitente->getUsername(),
                            'correo' => $remitente->getCorreo(),
                            'documento' => $remitente->getDocumento(),
                        ];
                    }
                }
                
                // Obtener información del destinatario
                if ($mensaje->getIdUsuarioDestinatario()) {
                    $destinatario = $this->usuarioService->getById($mensaje->getIdUsuarioDestinatario());
                    if ($destinatario) {
                        $mensajeArray['destinatario'] = [
                            'id_usuario' => $destinatario->getId(),
                            'nombre' => $destinatario->getNombre(),
                            'username' => $destinatario->getUsername(),
                            'correo' => $destinatario->getCorreo(),
                            'documento' => $destinatario->getDocumento(),
                        ];
                    }
                }
                
                $mensajesEnriquecidos[] = $mensajeArray;
            }
            
            return $this->successResponse(
                data: $mensajesEnriquecidos,
                message: 'Mensajes recibidos obtenidos exitosamente',
                title: 'Mensajes recibidos'
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

    /**
     * Marcar mensaje como leído
     */
    public function marcarLeido(int $id): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $marcado = $this->mensajeService->marcarComoLeido($id, $user->id_usuario);
            
            if (!$marcado) {
                return $this->notFoundResponse('Mensaje no encontrado');
            }

            $mensaje = $this->mensajeService->getById($id);
            return $this->successResponse(
                data: $mensaje->toArray(),
                message: 'Mensaje marcado como leído exitosamente',
                title: 'Mensaje leído'
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

    /**
     * Contar mensajes no leídos
     */
    public function contarNoLeidos(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse(
                    message: 'Usuario no autenticado',
                    code: 401,
                    codeError: '401',
                    title: 'Error de autenticación'
                );
            }

            $cantidad = $this->mensajeService->contarNoLeidos($user->id_usuario);
            return $this->successResponse(
                data: ['cantidad' => $cantidad],
                message: 'Cantidad de mensajes no leídos obtenida exitosamente',
                title: 'Mensajes no leídos'
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

    /**
     * Obtener un mensaje por ID
     */
    public function show(int $id): JsonResponse
    {
        try {
            $mensaje = $this->mensajeService->getById($id);
            
            if (!$mensaje) {
                return $this->notFoundResponse('Mensaje no encontrado');
            }

            $mensajeArray = $mensaje->toArray();
            
            // Obtener información del remitente
            if ($mensaje->getIdUsuarioRemitente()) {
                $remitente = $this->usuarioService->getById($mensaje->getIdUsuarioRemitente());
                if ($remitente) {
                    $mensajeArray['remitente'] = [
                        'id_usuario' => $remitente->getId(),
                        'nombre' => $remitente->getNombre(),
                        'username' => $remitente->getUsername(),
                        'correo' => $remitente->getCorreo(),
                        'documento' => $remitente->getDocumento(),
                    ];
                }
            }
            
            // Obtener información del destinatario
            if ($mensaje->getIdUsuarioDestinatario()) {
                $destinatario = $this->usuarioService->getById($mensaje->getIdUsuarioDestinatario());
                if ($destinatario) {
                    $mensajeArray['destinatario'] = [
                        'id_usuario' => $destinatario->getId(),
                        'nombre' => $destinatario->getNombre(),
                        'username' => $destinatario->getUsername(),
                        'correo' => $destinatario->getCorreo(),
                        'documento' => $destinatario->getDocumento(),
                    ];
                }
            }

            return $this->successResponse(
                data: $mensajeArray,
                message: 'Mensaje obtenido exitosamente',
                title: 'Detalle de mensaje'
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

