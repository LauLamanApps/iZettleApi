<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\DiscountCollection;
use LauLamanApps\IzettleApi\Client\Universal\ImageParser;
use Money\Currency;
use Money\Money;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

final class DiscountParser
{
    public static function createFromResponse(ResponseInterface $response): array
    {
        $products = [];
        $data = json_decode($response->getBody()->getContents(), true);

        foreach ($data as $product) {
            $products[] = self::parse($product);
        }

        return $products;
    }

    public static function parseArray($discounts): DiscountCollection
    {
        $discountCollection = new DiscountCollection();

        foreach ($discounts as $discount) {
            $discountCollection->add(self::parse($discount));
        }

        return $discountCollection;
    }
    
    private static function parse(array $product): Discount
    {
        return Discount::create(
            Uuid::fromString($product['uuid']),
            $product['name'],
            $product['description'],
            ImageParser::parseArray($product['imageLookupKeys']),
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
