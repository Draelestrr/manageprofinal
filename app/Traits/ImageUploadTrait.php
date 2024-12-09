<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageUploadTrait
{
    /**
     * Procesa y almacena una imagen.
     *
     * @param \Illuminate\Http\UploadedFile $file Archivo subido.
     * @param string $path Ruta de almacenamiento (relativa a 'public').
     * @return string|null Ruta de la imagen guardada o null si falla.
     */
    protected function uploadImage($file, $path = 'products'): ?string
    {
        if (!$file) {
            return null;
        }

        try {
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $fullPath = $path . '/' . $filename;

            Storage::disk('public')->putFileAs($path, $file, $filename, 'public'); // Método más robusto

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Error al subir imagen: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Elimina una imagen existente.
     *
     * @param string|null $path Ruta de la imagen (relativa a 'public').
     * @return bool True si se eliminó correctamente, false si no existe o falla.
     */
    protected function deleteExistingImage(?string $path): bool
    {
        if ($path === null || empty($path)) {
            return false; // No hace nada si la ruta es nula o vacía
        }

        try {
            return Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar imagen: ' . $e->getMessage());
            return false;
        }
    }
}
