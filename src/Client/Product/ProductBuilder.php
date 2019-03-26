<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Product\ProductCollection;
use LauLamanApps\IzettleApi\API\Universal\Vat;
use LauLamanApps\IzettleApi\Client\Universal\ImageBuilderInterface;
use Ramsey\Uuid\Uuid;

final class ProductBuilder implements ProductBuilderInterface
{
    private $imageBuilder;
    private $categoryBuilder;
    private $variantBuilder;

    public function __construct(
        CategoryBuilderInterface $categoryBuilder,
        ImageBuilderInterface $imageBuilder,
        VariantBuilderInterface $variantBuilder
    ) {
        $this->categoryBuilder = $categoryBuilder;
        $this->imageBuilder = $imageBuilder;
        $this->variantBuilder = $variantBuilder;
    }

    /**
     * @return Product[]
     */
    public function buildFromJson(string $json): array
    {
        $products = [];
        foreach (json_decode($json, true) as $purchase) {
            $products[] = $this->build($purchase);
        }

        return $products;
    }

    public function buildFromArray(array $data): ProductCollection
    {
        $productCollection = new ProductCollection();

        foreach ($data as $product) {
            $productCollection->add($this->build($product));
        }

        return $productCollection;
    }

    private function build(array $data): Product
    {
        return Product::create(
            Uuid::fromString($data['uuid']),
            $this->categoryBuilder->buildFromArray($data['categories']),
            $data['name'],
            $data['description'],
            $this->imageBuilder->buildFromArray($data['imageLookupKeys']),
            $this->variantBuilder->buildFromArray($data['variants']),
            $data['externalReference'],
            $data['etag'],
            new DateTime($data['updated']),
            Uuid::fromString($data['updatedBy']),
            new DateTime($data['created']),
            $data['vatPercentage'] != '0' ? new Vat($data['vatPercentage']) : null
        );
    }
}
