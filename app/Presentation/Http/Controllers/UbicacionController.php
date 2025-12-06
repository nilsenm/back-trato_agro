<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UbicacionController extends BaseController
{
    public function departamentos(): JsonResponse
    {
        try {
            $departamentos = DB::table('departamento')
                ->orderBy('nombre')
                ->get();
            
            return $this->successResponse(data: $departamentos, message: 'Departamentos obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function provincias(int $idDepartamento): JsonResponse
    {
        try {
            $provincias = DB::table('provincia')
                ->where('id_departamento', $idDepartamento)
                ->orderBy('nombre')
                ->get();
            
            return $this->successResponse(data: $provincias, message: 'Provincias obtenidas exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }

    public function distritos(int $idProvincia): JsonResponse
    {
        try {
            $distritos = DB::table('distrito')
                ->where('id_provincia', $idProvincia)
                ->orderBy('nombre')
                ->get();
            
            return $this->successResponse(data: $distritos, message: 'Distritos obtenidos exitosamente', title: 'Operación exitosa');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), code: 500, codeError: '500', title: 'Error del servidor', exception: $e);
        }
    }
}









