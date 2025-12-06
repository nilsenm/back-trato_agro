<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\ConsultaExternaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsultaExternaController extends BaseController
{
    public function __construct(
        private ConsultaExternaService $consultaExternaService
    ) {}

    public function consultarDni(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'dni' => 'required|string|size:8',
            ]);

            $resultado = $this->consultaExternaService->consultarDni($validated['dni']);
            
            if ($resultado === null) {
                return $this->errorResponse(message: 'No se pudieron obtener datos. Verifica la configuración de la API en .env', code: 404, codeError: '404', title: 'Datos no encontrados');
            }
            
            return $this->successResponse(data: $resultado, message: 'Consulta DNI realizada', title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function consultarRuc(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'ruc' => 'required|string|size:11',
            ]);

            $resultado = $this->consultaExternaService->consultarRuc($validated['ruc']);
            
            return $this->successResponse(data: $resultado, message: 'Consulta RUC realizada', title: 'Operación exitosa');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }
}

