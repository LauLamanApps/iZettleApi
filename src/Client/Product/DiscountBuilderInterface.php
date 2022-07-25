<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use LauLamanApps\IzettleApi\API\Product\DiscountCollection;
use LauLamanApps\IzettleApi\Client\Universal\BuilderInterface;

interface DiscountBuilderInterface extends BuilderInterface
{
    public function buildFromJson(string $json): array;

    public function buildFromArray(array $discounts): DiscountCollection;
}
