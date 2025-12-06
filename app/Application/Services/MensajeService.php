<?php

namespace App\Application\Services;

use App\Domain\Interfaces\MensajeRepositoryInterface;
use App\Domain\Interfaces\OfertaRepositoryInterface;

class MensajeService extends BaseService
{
    public function __construct(
        MensajeRepositoryInterface $repository,
        private OfertaRepositoryInterface $ofertaRepository
    ) {
        parent::__construct($repository);
    }

    public function enviarMensaje(array $data): array
    {
        // Si hay una oferta asociada, validar que existe
        if (isset($data['id_oferta']) && $data['id_oferta']) {
            $oferta = $this->ofertaRepository->find($data['id_oferta']);
            if (!$oferta) {
                throw new \Exception('Oferta no encontrada');
            }

            // Verificar que el remitente es parte de la oferta
            $esOfertante = $oferta->getIdUsuarioOfertante() === $data['id_usuario_remitente'];
            $esVendedor = $oferta->getIdUsuarioVendedor() === $data['id_usuario_remitente'];
            
            if (!$esOfertante && !$esVendedor) {
                throw new \Exception('No tienes permiso para enviar mensajes sobre esta oferta');
            }

            // El destinatario debe ser el otro usuario de la oferta
            $destinatarioEsperado = $esOfertante 
                ? $oferta->getIdUsuarioVendedor() 
                : $oferta->getIdUsuarioOfertante();
            
            if ($data['id_usuario_destinatario'] !== $destinatarioEsperado) {
                throw new \Exception('El destinatario debe ser el otro usuario de la oferta');
            }
        }

        $mensaje = $this->repository->create([
            'id_oferta' => $data['id_oferta'] ?? null,
            'id_usuario_remitente' => $data['id_usuario_remitente'],
            'id_usuario_destinatario' => $data['id_usuario_destinatario'],
            'mensaje' => $data['mensaje'],
            'leido' => false,
        ]);

        return $mensaje->toArray();
    }

    public function findByOferta(int $idOferta): array
    {
        return $this->repository->findByOferta($idOferta);
    }

    public function findByUsuarios(int $idUsuario1, int $idUsuario2): array
    {
        return $this->repository->findByUsuarios($idUsuario1, $idUsuario2);
    }

    public function findByUsuarioRemitente(int $idUsuario): array
    {
        return $this->repository->findByUsuarioRemitente($idUsuario);
    }

    public function findByUsuarioDestinatario(int $idUsuario): array
    {
        return $this->repository->findByUsuarioDestinatario($idUsuario);
    }

    public function marcarComoLeido(int $idMensaje, int $idUsuario): bool
    {
        $mensaje = $this->repository->find($idMensaje);
        
        if (!$mensaje) {
            throw new \Exception('Mensaje no encontrado');
        }

        if ($mensaje->getIdUsuarioDestinatario() !== $idUsuario) {
            throw new \Exception('No tienes permiso para marcar este mensaje como leído');
        }

        return $this->repository->marcarComoLeido($idMensaje);
    }

    public function contarNoLeidos(int $idUsuario): int
    {
        return $this->repository->contarNoLeidos($idUsuario);
    }
}

