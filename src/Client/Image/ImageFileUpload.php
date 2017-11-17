<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Image;

use Exception;
use LauLamanApps\IzettleApi\Client\Image\Exceptions\FileIsNotAnImageException;
use LauLamanApps\IzettleApi\Client\Image\Exceptions\ImageIsToSmallException;
use LauLamanApps\IzettleApi\Client\Image\Exceptions\ImageTypeNotAllowedException;
use LauLamanApps\IzettleApi\Client\Image\Exceptions\MaximumImageFileSizeExcededException;

final class ImageFileUpload implements ImageUploadRequestInterface
{
    const ALLOWED_FILE_TYPES = [
        IMAGETYPE_GIF => 'GIF',
        IMAGETYPE_JPEG => 'JPEG',
        IMAGETYPE_PNG => 'PNG',
        IMAGETYPE_BMP => 'BMP',
        IMAGETYPE_TIFF_II => 'TIFF',
        IMAGETYPE_TIFF_MM => 'TIFF'
    ];
    const MAX_FILE_SIZE_MB = 5;
    const MINIMAL_HEIGHT = 50;
    const MINIMAL_WIDTH = 50;

    private $imageFormat;
    private $imageData;

    public function __construct(string $filename)
    {
        $this->validateFile($filename);
        $this->imageFormat = self::ALLOWED_FILE_TYPES[exif_imagetype($filename)];
        $this->imageData = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode(file_get_contents($filename)));
    }

    public function getPostBodyData(): string
    {
        $data = [
            'imageFormat' => $this->imageFormat,
            'imageData' => $this->imageData,
        ];

        return json_encode($data);
    }

    private function validateFile(string $file): void
    {
        self::validateFileSize($file);
        self::validatedImageType($file);
        self::validateImageSize($file);
    }

    private static function validateFileSize($file): void
    {
        $maxFileSizeBytes = (self::MAX_FILE_SIZE_MB * 1024 * 1024);
        if (filesize($file) > $maxFileSizeBytes) {
            throw new MaximumImageFileSizeExcededException(sprintf('Max file size is \'%d Mb\'', self::MAX_FILE_SIZE_MB));
        }
    }

    private static function validatedImageType(string $file): void
    {
        $type = false;

        try {
            $type = exif_imagetype($file);
        } catch (Exception $e) {
            //-- $type is already false
        }

        if ($type === false) {
            throw new FileIsNotAnImageException();
        }

        if (!array_key_exists($type, self::ALLOWED_FILE_TYPES)) {
            throw new ImageTypeNotAllowedException(
                sprintf('Allowed: %s', implode(', ', self::ALLOWED_FILE_TYPES))
            );
        }
    }

    private static function validateImageSize(string $file): void
    {
        list($width, $height) = getimagesize($file);

        if ($width < self::MINIMAL_WIDTH or $height < self::MINIMAL_HEIGHT) {
            throw new ImageIsToSmallException(
                sprintf(
                    'Minimal image dimensions not met. Required: \'%dx%d\' Provided: \'%dx%d\'',
                    self::MINIMAL_HEIGHT,
                    self::MINIMAL_WIDTH,
                    $height,
                    $width
                )
            );
        }
    }
}
