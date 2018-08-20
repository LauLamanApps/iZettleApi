<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use LauLamanApps\IzettleApi\API\Product\Library;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

final class LibraryBuilder implements LibraryBuilderInterface
{
    private $productBuilder;
    private $discountBuilder;

    public function __construct(
        ProductBuilderInterface $productBuilder,
        DiscountBuilderInterface $discountBuilder
    ) {
        $this->productBuilder = $productBuilder;
        $this->discountBuilder = $discountBuilder;
    }

    public function buildFromJson(string $json): Library
    {
        $data = json_decode($json, true);
        $fromEventLogUuid = $data['fromEventLogUuid'] ? Uuid::fromString($data['fromEventLogUuid']) : null;

        return new Library(
            $fromEventLogUuid,
            Uuid::fromString($data['untilEventLogUuid']),
            $this->productBuilder->buildFromArray($data['products']),
            $this->discountBuilder->buildFromArray($data['discounts']),
            $this->productBuilder->buildFromArray($data['deletedProducts']),
            $this->discountBuilder->buildFromArray($data['deletedDiscounts'])
        );
    }
}
