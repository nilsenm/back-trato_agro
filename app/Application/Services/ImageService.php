<?php

namespace App\Application\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Guarda una imagen desde base64 a storage y retorna la URL pública
     * 
     * @param string|null $base64Image Imagen en base64 (puede incluir data:image/...)
     * @param string $folder Carpeta donde guardar (ej: 'productos', 'stocks')
     * @return string|null URL pública de la imagen o null si no se proporcionó imagen
     */
    public function saveBase64Image(?string $base64Image, string $folder = 'productos'): ?string
    {
        if (!$base64Image) {
            return null;
        }

        // Si es una URL, retornarla directamente
        if (filter_var($base64Image, FILTER_VALIDATE_URL)) {
            return $base64Image;
        }

        // Si no es base64 válido, retornar null
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            // Si parece ser una ruta relativa o nombre de archivo, retornarlo
            return $base64Image;
        }

        try {
            // Extraer el tipo de imagen (png, jpg, jpeg, etc.)
            $imageType = $matches[1];
            
            // Validar tipo de imagen permitido
            $allowedTypes = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
            if (!in_array(strtolower($imageType), $allowedTypes)) {
                throw new \Exception('Tipo de imagen no permitido. Solo se permiten: ' . implode(', ', $allowedTypes));
            }

            // Remover el prefijo data:image/...;base64,
            $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
            
            // Decodificar base64
            $imageData = base64_decode($base64Data, true);
            
            if ($imageData === false) {
                throw new \Exception('Error al decodificar la imagen base64');
            }

            // Generar nombre único para el archivo
            $fileName = Str::uuid() . '.' . $imageType;
            
            // Mapear folder a la carpeta pública correcta
            // 'productos' -> 'prouctos' (mantener el typo del usuario)
            $publicFolder = $folder === 'productos' ? 'prouctos' : $folder;
            
            // Ruta completa donde guardar: /public/prouctos/nombre_archivo.jpg
            $publicPath = public_path($publicFolder);
            
            // Crear la carpeta si no existe
            if (!File::exists($publicPath)) {
                File::makeDirectory($publicPath, 0755, true);
            }
            
            // Ruta completa del archivo
            $filePath = $publicPath . '/' . $fileName;
            
            // Guardar el archivo directamente en public/prouctos/
            File::put($filePath, $imageData);

            // Retornar la URL pública relativa: /prouctos/nombre_archivo.jpg
            return '/' . $publicFolder . '/' . $fileName;
            
        } catch (\Exception $e) {
            throw new \Exception('Error al guardar la imagen: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una imagen del storage
     * 
     * @param string|null $imageUrl URL o ruta de la imagen
     * @return bool True si se eliminó correctamente
     */
    public function deleteImage(?string $imageUrl): bool
    {
        if (!$imageUrl) {
            return false;
        }

        // Si es una URL externa, no intentar eliminarla
        if (filter_var($imageUrl, FILTER_VALIDATE_URL) && !str_contains($imageUrl, '/prouctos/') && !str_contains($imageUrl, '/storage/')) {
            return false;
        }

        try {
            // Si es una ruta relativa que empieza con /, convertir a ruta completa
            if (str_starts_with($imageUrl, '/')) {
                $filePath = public_path($imageUrl);
            } else {
                // Intentar extraer la ruta desde storage si es una URL de storage
                if (str_contains($imageUrl, '/storage/')) {
                    $path = str_replace(Storage::disk('public')->url(''), '', $imageUrl);
                    $path = ltrim($path, '/');
                    $filePath = storage_path('app/public/' . $path);
                } else {
                    // Asumir que es una ruta relativa
                    $filePath = public_path($imageUrl);
                }
            }
            
            // Eliminar el archivo si existe
            if (File::exists($filePath)) {
                return File::delete($filePath);
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}

