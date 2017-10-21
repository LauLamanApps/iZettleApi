<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Product;

use Ramsey\Uuid\UuidInterface;

final class DiscountCollection
{
    /** @var Discount[] */
    private $collection = [];

    public function __construct(?array $discounts = [])
    {
        foreach ($discounts as $discount) {
            $this->add($discount);
        }
    }

    public function add(Discount $discount): void
    {
        $this->collection[(string) $discount->getUuid()] = $discount;
    }

    public function remove(Discount $discount): void
    {
        unset($this->collection[(string) $discount->getUuid()]);
    }

    public function get(UuidInterface $key): Discount
    {
        return $this->collection[(string) $key];
    }

    /** @return Discount[] */
    public function getAll(): array
    {
        return $this->collection;
    }
}
