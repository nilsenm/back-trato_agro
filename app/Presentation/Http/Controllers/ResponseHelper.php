<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

/**
 * Helper para generar respuestas en formato ApplicationResponse
 */
class ResponseHelper
{
    /**
     * Genera una respuesta exitosa
     */
    public static function success(
        $data = [],
        string $message = 'Operación exitosa',
        int $code = Response::HTTP_OK,
        string $title = 'Éxito',
        array $otherData = [],
        array $filter = []
    ): JsonResponse {
        $dataArray = self::normalizeData($data);

        return response()->json([
            'status' => true,
            'code' => $code,
            'data' => $dataArray,
            'otherData' => $otherData,
            'filter' => $filter,
            'title' => $title,
            'message' => $message,
            'codeError' => '',
            'messageError' => '',
            'line' => 0,
            'file' => '',
        ], $code);
    }

    /**
     * Genera una respuesta de error
     */
    public static function error(
        string $message,
        int $code = Response::HTTP_BAD_REQUEST,
        string $codeError = '',
        string $title = 'Error',
        $errors = null,
        Throwable $exception = null
    ): JsonResponse {
        $otherData = [];
        if ($errors) {
            $otherData['errors'] = is_array($errors) ? $errors : ['validation' => $errors];
        }

        $line = 0;
        $file = '';
        if ($exception) {
            $line = $exception->getLine();
            $file = $exception->getFile();
        }

        return response()->json([
            'status' => false,
            'code' => $code,
            'data' => [],
            'otherData' => $otherData,
            'filter' => [],
            'title' => $title,
            'message' => $message,
            'codeError' => $codeError ?: (string)$code,
            'messageError' => $message,
            'line' => $line,
            'file' => $file,
        ], $code);
    }

    /**
     * Normaliza los datos a array
     */
    private static function normalizeData($data): array
    {
        if (is_array($data)) {
            return $data;
        }

        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                return $data->toArray();
            }
            if ($data instanceof \Illuminate\Support\Collection) {
                return $data->toArray();
            }
            return [$data];
        }

        return $data === null ? [] : [$data];
    }
}









