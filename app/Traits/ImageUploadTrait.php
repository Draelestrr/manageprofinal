<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageUploadTrait
{
    /**
     * Procesa y almacena una imagen
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path Ruta de almacenamiento
     * @return string|null Ruta de la imagen guardada
     */
    protected function uploadImage($file, $path = 'products')
    {
        if (!$file) return null;

        try {
            // Generar nombre único para el archivo
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $fullPath = $path . '/' . $filename;

            // Guardar imagen
            $file->storeAs('public/' . $path, $filename);

            return $fullPath;
        } catch (\Exception $e) {
            // Registrar error en el log
            \Log::error('Error al subir imagen: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Eliminar imagen existente
     *
     * @param string|null $path Ruta de la imagen
     */
    protected function deleteExistingImage(?string $path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
