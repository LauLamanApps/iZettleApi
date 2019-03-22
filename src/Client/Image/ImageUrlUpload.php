<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Image;

final class ImageUrlUpload implements ImageUploadRequestInterface
{
    const ALLOWED_FILE_TYPES = [
        'gif'  => 'GIF',
        'jpeg' => 'JPEG',
        'jpg'  => 'JPEG',
        'png'  => 'PNG',
        'bmp'  => 'BMP',
    ];

    private $imageUrl;
    private $imageFormat;

    public function __construct(string $imageUrl)
    {
        $this->imageUrl     = $imageUrl;
        $this->imageFormat  = self::ALLOWED_FILE_TYPES[
            strtolower(array_values(array_slice(explode('.', $imageUrl), -1))[0])
        ];
    }

    public function getPostBodyData(): string
    {
        $data = [
            'imageFormat'   => $this->imageFormat,
            'imageUrl'      => $this->imageUrl
        ];

        return json_encode($data);
    }
}
