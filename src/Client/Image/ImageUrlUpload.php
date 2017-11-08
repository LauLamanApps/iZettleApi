<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Image;

final class ImageUrlUpload implements ImageUploadRequestInterface
{
    private $imageUrl;

    public function __construct(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public function getUploadRequest(): array
    {
        return [
            'imageUrl' => $this->imageUrl
        ];
    }
}
