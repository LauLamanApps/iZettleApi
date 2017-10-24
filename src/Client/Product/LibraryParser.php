<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use LauLamanApps\IzettleApi\API\Product\Library;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

final class LibraryParser
{
    public static function createFromResponse(ResponseInterface $response): Library
    {
        $data = json_decode($response->getBody()->getContents(), true);

        return new Library(
            Uuid::fromString($data['fromEventLogUuid']),
            Uuid::fromString($data['untilEventLogUuid']),
            ProductParser::parseArray($data['products']),
            DiscountParser::parseArray($data['discounts']),
            ProductParser::parseArray($data['deletedProducts']),
            DiscountParser::parseArray($data['deletedDiscounts'])
        );
    }
}
