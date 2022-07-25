<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\DiscountCollection;
use LauLamanApps\IzettleApi\Client\Universal\ImageBuilderInterface;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\Uuid;

final class DiscountBuilder implements DiscountBuilderInterface
{
    private $imageBuilder;

    public function __construct(ImageBuilderInterface $imageBuilder)
    {
        $this->imageBuilder = $imageBuilder;
    }

    /**
     * @return Discount[]
     */
    public function buildFromJson(string $json): array
    {
        $products = [];
        $data = json_decode($json, true);
        foreach ($data as $product) {
            $products[] = $this->build($product);
        }

        return $products;
    }

    public function buildFromArray(array $discounts): DiscountCollection
    {
        $discountCollection = new DiscountCollection();

        foreach ($discounts as $discount) {
            $discountCollection->add($this->build($discount));
        }

        return $discountCollection;
    }

    private function build(array $product): Discount
    {
        return Discount::create(
            Uuid::fromString($product['uuid']),
            $product['name'],
            $product['description'],
            $this->imageBuilder->buildFromArray($product['imageLookupKeys']),
            $product['amount'] ? new Money($product['amount']['amount'], new Currency($product['amount']['currencyId'])) : null,
            $product['percentage'] ? (float) $product['percentage'] : null,
            $product['externalReference'],
            $product['etag'],
            new DateTime($product['updated']),
            Uuid::fromString($product['updatedBy']),
            new DateTime($product['created'])
        );
    }
}
