<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Image;

interface ImageUploadRequestInterface
{
    public function getUploadRequest(): array;
}
