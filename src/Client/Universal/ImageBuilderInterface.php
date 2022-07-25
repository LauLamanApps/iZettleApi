<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Universal;

use LauLamanApps\IzettleApi\API\Image;
use LauLamanApps\IzettleApi\API\ImageCollection;

interface ImageBuilderInterface extends BuilderInterface
{
    public function buildFromArray(array $images): ImageCollection;

    public function buildFromJson(string $json): Image;
}
