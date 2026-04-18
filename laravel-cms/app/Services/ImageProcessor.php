<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageProcessor
{
    /**
     * EXIF orientation values to rotation angles
     * Reference: https://en.wikipedia.org/wiki/Exchangeable_image_file_format
     */
    private const EXIF_ORIENTATION_TO_ROTATION = [
        2 => 'h',  // Flip horizontally
        3 => 180,  // Rotate 180°
        4 => 'v',  // Flip vertically
        5 => 'vr90', // Vertical flip + rotate 90° CW
        6 => 270,  // Rotate 270° CW (or 90° CCW)
        7 => 'hr90', // Horizontal flip + rotate 90° CW
        8 => 90,   // Rotate 90° CW
    ];

    /**
     * Process image file to fix EXIF orientation and save
     *
     * @param UploadedFile $file
     * @param string $destinationPath Full file path (including directory)
     * @return bool
     */
    public static function processAndSave(UploadedFile $file, string $destinationPath): bool
    {
        // Save original file first
        $tempPath = $file->getRealPath();
        
        // Try to fix EXIF orientation
        if (!self::fixExifOrientation($tempPath, $destinationPath)) {
            // If EXIF fixing fails, just copy the file
            return copy($tempPath, $destinationPath);
        }
        
        return true;
    }

    /**
     * Read EXIF orientation and apply rotation
     *
     * @param string $sourcePath
     * @param string $destinationPath
     * @return bool
     */
    private static function fixExifOrientation(string $sourcePath, string $destinationPath): bool
    {
        // Check if exif extension is available
        if (!extension_loaded('exif')) {
            return false;
        }

        try {
            $exif = @exif_read_data($sourcePath);
            if ($exif === false || !isset($exif['Orientation'])) {
                // No EXIF data or no orientation, just copy the file
                return copy($sourcePath, $destinationPath);
            }

            $orientation = (int) $exif['Orientation'];

            // If orientation is 1 (normal), no rotation needed
            if ($orientation === 1) {
                return copy($sourcePath, $destinationPath);
            }

            // Load image based on file type
            $image = self::loadImage($sourcePath);
            if ($image === null) {
                return false;
            }

            // Apply rotation based on orientation
            $image = self::rotateImageByOrientation($image, $orientation);
            
            if ($image === null) {
                return false;
            }

            // Save the corrected image
            return self::saveImage($image, $destinationPath, $sourcePath);
        } catch (\Exception $e) {
            // If anything goes wrong, fall back to copying original file
            return copy($sourcePath, $destinationPath);
        }
    }

    /**
     * Load image from file
     *
     * @param string $filePath
     * @return \GdImage|null
     */
    private static function loadImage(string $filePath): ?\GdImage
    {
        $mimeType = mime_content_type($filePath);

        return match ($mimeType) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($filePath),
            'image/png' => @imagecreatefrompng($filePath),
            'image/gif' => @imagecreatefromgif($filePath),
            'image/webp' => @imagecreatefromwebp($filePath),
            default => null,
        };
    }

    /**
     * Rotate image based on EXIF orientation value
     *
     * @param \GdImage $image
     * @param int $orientation
     * @return \GdImage|null
     */
    private static function rotateImageByOrientation(\GdImage $image, int $orientation): ?\GdImage
    {
        return match ($orientation) {
            2 => self::flipHorizontal($image),
            3 => imagerotate($image, 180, 0),
            4 => self::flipVertical($image),
            5 => self::flipVertical(imagerotate($image, 90, 0) ?: $image),
            6 => imagerotate($image, 270, 0),
            7 => self::flipHorizontal(imagerotate($image, 90, 0) ?: $image),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    /**
     * Flip image horizontally
     *
     * @param \GdImage $image
     * @return \GdImage
     */
    private static function flipHorizontal(\GdImage $image): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $flipped = imagecreatetruecolor($width, $height);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $width - $x - 1, $y);
                imagesetpixel($flipped, $x, $y, $color);
            }
        }

        imagedestroy($image);
        return $flipped;
    }

    /**
     * Flip image vertically
     *
     * @param \GdImage $image
     * @return \GdImage
     */
    private static function flipVertical(\GdImage $image): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $flipped = imagecreatetruecolor($width, $height);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $height - $y - 1);
                imagesetpixel($flipped, $x, $y, $color);
            }
        }

        imagedestroy($image);
        return $flipped;
    }

    /**
     * Save image to file
     *
     * @param \GdImage $image
     * @param string $destinationPath
     * @param string $sourcePath
     * @return bool
     */
    private static function saveImage(\GdImage $image, string $destinationPath, string $sourcePath): bool
    {
        $mimeType = mime_content_type($sourcePath);
        $success = false;

        try {
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $success = imagejpeg($image, $destinationPath, 90);
                    break;
                case 'image/png':
                    $success = imagepng($image, $destinationPath, 6);
                    break;
                case 'image/gif':
                    $success = imagegif($image, $destinationPath);
                    break;
                case 'image/webp':
                    $success = imagewebp($image, $destinationPath, 90);
                    break;
            }
        } finally {
            imagedestroy($image);
        }

        return $success;
    }
}
