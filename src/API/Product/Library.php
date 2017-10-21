<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Product;

use Ramsey\Uuid\UuidInterface;

final class Library
{
    private $fromEventLogUuid;
    private $untilEventLogUuid;
    private $products;
    private $discounts;
    private $deletedProducts;
    private $deletedDiscounts;

    public function __construct(
        UuidInterface $fromEventLogUuid,
        UuidInterface $untilEventLogUuid,
        ProductCollection $products,
        DiscountCollection $discounts,
        ProductCollection $deletedProducts,
        DiscountCollection $deletedDiscounts
    ) {
        $this->fromEventLogUuid = $fromEventLogUuid;
        $this->untilEventLogUuid = $untilEventLogUuid;
        $this->products = $products;
        $this->discounts = $discounts;
        $this->deletedProducts = $deletedProducts;
        $this->deletedDiscounts = $deletedDiscounts;
    }

    public function getFromEventLogUuid(): UuidInterface
    {
        return $this->fromEventLogUuid;
    }

    public function getUntilEventLogUuid(): UuidInterface
    {
        return $this->untilEventLogUuid;
    }

    public function getProducts(): ProductCollection
    {
        return $this->products;
    }

    public function getDiscounts(): DiscountCollection
    {
        return $this->discounts;
    }

    public function getDeletedProducts(): ProductCollection
    {
        return $this->deletedProducts;
    }

    public function getDeletedDiscounts(): DiscountCollection
    {
        return $this->deletedDiscounts;
    }
}
