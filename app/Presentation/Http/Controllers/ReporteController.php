<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\ReporteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReporteController extends BaseController
{
    public function __construct(
        private ReporteService $reporteService
    ) {}

    public function ventasPorCategoria(Request $request, int $idCategoria): JsonResponse
    {
        try {
            $fechaDesde = $request->input('fecha_desde');
            
            $ventas = $this->reporteService->obtenerVentasPorCategoria($idCategoria, $fechaDesde);
            
            return $this->successResponse(data: $ventas, message: 'Reporte de ventas obtenido exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }
}









