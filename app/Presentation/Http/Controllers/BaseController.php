<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

abstract class BaseController
{
    /**
     * Formato estándar de respuesta ApplicationResponse
     */
    protected function applicationResponse(
        bool $status,
        int $code,
        $data = [],
        array $otherData = [],
        array $filter = [],
        string $title = '',
        string $message = '',
        string $codeError = '',
        string $messageError = '',
        int $line = 0,
        string $file = ''
    ): JsonResponse {
        // Convertir data a array si es necesario
        $dataArray = $this->normalizeData($data);

        $response = [
            'status' => $status,
            'code' => $code,
            'data' => $dataArray,
            'otherData' => $otherData,
            'filter' => $filter,
            'title' => $title,
            'message' => $message,
            'codeError' => $codeError,
            'messageError' => $messageError,
            'line' => $line,
            'file' => $file,
        ];

        return response()->json($response, $code);
    }

    /**
     * Respuesta exitosa
     */
    protected function successResponse(
        $data = [],
        string $message = 'Operación exitosa',
        int $code = Response::HTTP_OK,
        string $title = 'Éxito',
        array $otherData = [],
        array $filter = []
    ): JsonResponse {
        return $this->applicationResponse(
            status: true,
            code: $code,
            data: $data,
            otherData: $otherData,
            filter: $filter,
            title: $title,
            message: $message
        );
    }

    /**
     * Respuesta de error
     */
    protected function errorResponse(
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

        return $this->applicationResponse(
            status: false,
            code: $code,
            data: [],
            otherData: $otherData,
            filter: [],
            title: $title,
            message: $message,
            codeError: $codeError ?: (string)$code,
            messageError: $message,
            line: $line,
            file: $file
        );
    }

    /**
     * Respuesta no encontrado
     */
    protected function notFoundResponse(
        string $message = 'Recurso no encontrado',
        string $title = 'No encontrado'
    ): JsonResponse {
        return $this->errorResponse(
            message: $message,
            code: Response::HTTP_NOT_FOUND,
            codeError: '404',
            title: $title
        );
    }

    /**
     * Respuesta de validación fallida
     */
    protected function validationErrorResponse(
        $errors,
        string $message = 'Error de validación',
        int $code = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        return $this->errorResponse(
            message: $message,
            code: $code,
            codeError: '422',
            title: 'Error de validación',
            errors: $errors
        );
    }

    /**
     * Normaliza los datos a array, procesando objetos recursivamente
     */
    private function normalizeData($data): array
    {
        // Si ya es un array, procesar cada elemento
        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                // Si el valor es escalar, mantenerlo como está
                if (!is_array($value) && !is_object($value)) {
                    $result[$key] = $value;
                } else {
                    // Si es objeto o array, normalizarlo recursivamente
                    $normalizedValue = $this->normalizeData($value);
                    $result[$key] = $normalizedValue;
                }
            }
            return $result;
        }

        // Si es un objeto, convertirlo a array
        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                return $this->normalizeData($data->toArray());
            }
            if ($data instanceof \Illuminate\Support\Collection) {
                return $this->normalizeData($data->toArray());
            }
            // Si es un objeto sin toArray, intentar convertir a array
            return $this->normalizeData((array) $data);
        }

        // Valores escalares o null - envolver en array solo si es necesario
        if ($data === null) {
            return [];
        }

        // Para valores escalares en el nivel raíz, envolver en array
        return [$data];
    }
}

