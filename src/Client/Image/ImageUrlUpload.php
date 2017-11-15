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

    public function getPostBodyData(): string
    {
        $data = [
            'imageUrl' => $this->imageUrl
        ];

        return json_encode($data);
    }
}
