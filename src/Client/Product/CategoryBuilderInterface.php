<?php

namespace LauLamanApps\IzettleApi\Client\Product;

use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\Client\Universal\BuilderInterface;

interface CategoryBuilderInterface extends BuilderInterface
{
    /**
     * @return Category[]
     */
    public function buildFromJson(string $json): array;

    public function buildFromArray($categories): CategoryCollection;
}
