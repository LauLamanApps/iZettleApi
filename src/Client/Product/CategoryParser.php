<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

final class CategoryParser
{
    public static function createFromResponse(ResponseInterface $response): array
    {
        $categories = [];
        $data = json_decode($response->getBody()->getContents(), true);

        foreach ($data as $category) {
            $categories[] = self::createFromArray($category);
        }

        return $categories;
    }

    public static function parseArray($categories)
    {
        $collection = new CategoryCollection();

        foreach ($categories as $category) {
            $collection->add(self::createFromArray($category));
        }

        return $collection;
    }

    private static function createFromArray(array $category): Category
    {
        return Category::create(
            Uuid::fromString($category['uuid']),
            $category['name'],
            $category['etag'],
            new DateTime($category['updated']),
            Uuid::fromString($category['updatedBy']),
            new DateTime($category['created'])
        );
    }
}
