<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Client\Product;

use LauLamanApps\iZettleApi\API\Product\Variant;
use LauLamanApps\iZettleApi\API\Product\VariantCollection;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\Uuid;

final class VariantParser
{
    public static function parseArray($variants)
    {
        $collection = new VariantCollection();

        foreach ($variants as $variant) {
            $collection->add(self::parse($variant));
        }

        return $collection;
    }

    private static function parse(array $variant): Variant
    {
        return Variant::create(
            Uuid::fromString($variant['uuid']),
            $variant['name'],
            $variant['description'],
            $variant['sku'],
            $variant['barcode'],
            (int) $variant['defaultQuantity'],
            $variant['unitName'],
            new Money($variant['price']['amount'], new Currency($variant['price']['currencyId'])),
            $variant['costPrice'] ? new Money($variant['costPrice']['amount'], new Currency($variant['costPrice']['currencyId'])) : null,
            (float) $variant['vatPercentage']
        );
    }
}
