<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\Product;
use Money\Currency;

interface ProductBuilderInterface
{
    /**
     * @return Product[]
     */
    public function buildFromArray(array $products, Currency $currency): array;
}
