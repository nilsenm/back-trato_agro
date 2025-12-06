<?php

namespace App\Application\Services;

use App\Domain\Entities\Usuario;
use App\Domain\Interfaces\UsuarioRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function __construct(
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function login(string $identificador, string $clave): array
    {
        // Intentar buscar por usuario primero, luego por correo
        $usuario = $this->usuarioRepository->findByUsuario($identificador);
        
        if (!$usuario) {
            // Si no se encuentra por usuario, intentar por correo
            $usuario = $this->usuarioRepository->findByCorreo($identificador);
        }
        
        if (!$usuario) {
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }

        // Verificar contraseña (si está hasheada) o comparar directamente si está en texto plano
        $claveValida = $usuario->getClave() === $clave || Hash::check($clave, $usuario->getClave());
        
        if (!$claveValida) {
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }

        try {
            // Obtener el modelo para generar el token
            $model = \App\Infrastructure\Models\UsuarioModel::find($usuario->getId());
            
            if (!$model) {
                return [
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ];
            }

            $token = JWTAuth::fromUser($model);
            
            $ttl = config('jwt.ttl', 60); // TTL en minutos, por defecto 60
            
            return [
                'success' => true,
                'message' => 'Login exitoso',
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $ttl * 60, // Convertir a segundos
                'usuario' => [
                    'id' => $usuario->getId(),
                    'documento' => $usuario->getDocumento(),
                    'usuario' => $usuario->getUsuario(),
                    'nombre' => $usuario->getNombre(),
                    'correo' => $usuario->getCorreo(),
                    'tipo_vendedor' => $usuario->getTipoVendedor(),
                    'tipo_persona' => $usuario->getTipoPersona(),
                ]
            ];
        } catch (JWTException $e) {
            return [
                'success' => false,
                'message' => 'No se pudo crear el token'
            ];
        }
    }

    public function me(): ?array
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return null;
            }

            $usuario = $this->usuarioRepository->find($user->id_usuario);
            
            if (!$usuario) {
                return null;
            }

            return [
                'id' => $usuario->getId(),
                'documento' => $usuario->getDocumento(),
                'usuario' => $usuario->getUsuario(),
                'nombre' => $usuario->getNombre(),
                'correo' => $usuario->getCorreo(),
                'tipo_vendedor' => $usuario->getTipoVendedor(),
                'tipo_persona' => $usuario->getTipoPersona(),
            ];
        } catch (JWTException $e) {
            return null;
        }
    }

    public function logout(): bool
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return true;
        } catch (JWTException $e) {
            return false;
        }
    }

    public function refresh(): array
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            
            $ttl = config('jwt.ttl', 60); // TTL en minutos, por defecto 60
            
            return [
                'success' => true,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $ttl * 60, // Convertir a segundos
            ];
        } catch (JWTException $e) {
            return [
                'success' => false,
                'message' => 'No se pudo refrescar el token'
            ];
        }
    }
}

