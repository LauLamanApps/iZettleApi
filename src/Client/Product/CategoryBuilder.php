<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

final class CategoryBuilder implements CategoryBuilderInterface
{
    /**
     * @return Category[]
     */
    public function buildFromJson(string $json): array
    {
        $categories = [];
        foreach (json_decode($json, true) as $category) {
            $categories[] = $this->build($category);
        }

        return $categories;
    }

    public function buildFromArray($categories): CategoryCollection
    {
        $collection = new CategoryCollection();

        foreach ($categories as $category) {
            $collection->add($this->build($category));
        }

        return $collection;
    }

    private function build(array $category): Category
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
