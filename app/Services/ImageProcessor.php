<?php

namespace App\Services;

use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageProcessor
{
    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function processImage($file)
    {
        try {
            // Generar nombre único
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'products/' . $fileName;
            $fullPath = storage_path('app/public/' . $path);

            // Asegurar directorio
            if (!is_dir(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Abrir imagen
            $image = $this->imagine->open($file->getRealPath());

            // Obtener dimensiones originales
            $originalSize = $image->getSize();
            $maxWidth = 800;
            $maxHeight = 600;

            // Redimensionar manteniendo proporción
            if ($originalSize->getWidth() > $maxWidth || $originalSize->getHeight() > $maxHeight) {
                $image->resize(
                    new Box($maxWidth, $maxHeight),
                    ImageInterface::THUMBNAIL_INSET
                );
            }

            // Guardar con compresión
            $image->save($fullPath, [
                'quality' => 75
            ]);

            return $path;

        } catch (\Exception $e) {
            Log::error('Error procesando imagen: ' . $e->getMessage());
            return $this->fallbackUpload($file);
        }
    }

    public function fallbackUpload($file)
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('products', $fileName, 'public');
    }
}
