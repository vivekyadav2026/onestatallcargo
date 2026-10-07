<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Store an uploaded file. If it's an image, compress it to be under ~200KB.
     * 
     * @param UploadedFile $file
     * @param string $path
     * @param string $disk
     * @return string
     */
    public static function uploadAndCompress(UploadedFile $file, $path = 'uploads', $disk = 'public')
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

        if (!$isImage) {
            // It's a PDF or something else, store normally
            return $file->store($path, $disk);
        }

        // It is an image, let's compress it using GD
        $sourcePath = $file->getRealPath();
        
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = @imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                $image = @imagecreatefrompng($sourcePath);
                break;
            case 'gif':
                $image = @imagecreatefromgif($sourcePath);
                break;
            case 'webp':
                $image = @imagecreatefromwebp($sourcePath);
                break;
            default:
                $image = false;
        }

        if (!$image) {
            // Failed to load, store normally
            return $file->store($path, $disk);
        }

        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        // Calculate new dimensions (max 1200px width/height to help compression)
        $maxDim = 1200;
        if ($width > $maxDim || $height > $maxDim) {
            $ratio = $width / $height;
            if ($ratio > 1) {
                $newWidth = $maxDim;
                $newHeight = $maxDim / $ratio;
            } else {
                $newWidth = $maxDim * $ratio;
                $newHeight = $maxDim;
            }
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            
            // Handle transparency for PNG/GIF
            if ($extension == 'png' || $extension == 'webp') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
            }
            
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            $imageToSave = $resized;
        } else {
            $imageToSave = $image;
        }

        $filename = Str::random(40) . '.jpg'; // Convert all to jpg for better compression
        $tempPath = sys_get_temp_dir() . '/' . $filename;
        
        // Save with 60% quality which usually gets < 200kb for 1200px images
        imagejpeg($imageToSave, $tempPath, 60);

        // Free memory
        imagedestroy($image);
        if (isset($resized)) {
            imagedestroy($resized);
        }

        // If for some reason compression made it worse or failed, fallback
        if (!file_exists($tempPath)) {
            return $file->store($path, $disk);
        }

        // Move to final storage
        $finalPath = $path . '/' . $filename;
        Storage::disk($disk)->put($finalPath, file_get_contents($tempPath));
        
        // Clean up temp
        @unlink($tempPath);

        return $finalPath;
    }
}
