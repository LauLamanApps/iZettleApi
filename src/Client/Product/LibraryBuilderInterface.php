<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use LauLamanApps\IzettleApi\API\Product\Library;

interface LibraryBuilderInterface
{
    public function buildFromJson(string $json): Library;
}
