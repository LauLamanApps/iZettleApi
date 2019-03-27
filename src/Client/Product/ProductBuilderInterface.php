<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use LauLamanApps\IzettleApi\API\Product\ProductCollection;

interface ProductBuilderInterface
{
    public function buildFromJson(string $json): array;

    public function buildFromArray(array $products): ProductCollection;

    public function buildSingleFromJson(string $json);
}
