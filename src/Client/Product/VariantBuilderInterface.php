<?php

namespace LauLamanApps\IzettleApi\Client\Product;

use LauLamanApps\IzettleApi\API\Product\VariantCollection;

interface VariantBuilderInterface
{
    public function buildFromArray(array $data): VariantCollection;
}
