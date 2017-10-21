<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Product;

use Ramsey\Uuid\UuidInterface;

final class VariantCollection
{
    /** @var Variant[] */
    private $collection = [];

    public function __construct(?array $variants = [])
    {
        foreach ($variants as $variant) {
            $this->add($variant);
        }
    }

    public function add(Variant $variant): void
    {
        $this->collection[(string) $variant->getUuid()] = $variant;
    }

    public function remove(Variant $variant): void
    {
        unset($this->collection[(string)$variant->getUuid()]);
    }

    public function get(UuidInterface $key): Variant
    {
        return $this->collection[(string)$key];
    }

    /** @return Variant[] */
    public function getAll(): array
    {
        return $this->collection;
    }

    public function getCreateDataArray(): array
    {
        $data = [];
        foreach ($this->collection as $variant) {
            $data[] = $variant->getCreateDataArray();
        }

        return $data;
    }
}
