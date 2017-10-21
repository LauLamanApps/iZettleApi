<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Product;

use Ramsey\Uuid\UuidInterface;

final class ProductCollection
{
    /** @var Product[] */
    private $collection = [];

    public function __construct(?array $products = [])
    {
        foreach ($products as $product) {
            $this->add($product);
        }
    }

    public function add(Product $product): void
    {
        $this->collection[(string) $product->getUuid()] = $product;
    }

    public function remove(Product $product): void
    {
        unset($this->collection[(string) $product->getUuid()]);
    }

    public function get(UuidInterface $key): Product
    {
        return $this->collection[(string) $key];
    }

    /** @return Product[] */
    public function getAll(): array
    {
        return $this->collection;
    }
}
