<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Product;

use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Product\ProductCollection;
use LauLamanApps\IzettleApi\API\Product\VariantCollection;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class ProductCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function productCollection(): void
    {
        $product1 = $this->getProductWithUuid();
        $product2 = $this->getProductWithUuid();
        $product3 = $this->getProductWithUuid();

        $productCollection = new ProductCollection([$product1, $product2, $product3]);
        $productCollection->add($product3);// add product 3 again it should only end up once in the collection

        //-- Check if collection contains all 3 products
        $collection = $productCollection->getAll();
        self::assertEquals(3, count($collection));
        self::assertEquals($product1, $collection[(string) $product1->getUuid()]);
        self::assertEquals($product2, $collection[(string) $product2->getUuid()]);
        self::assertEquals($product3, $collection[(string) $product3->getUuid()]);

        $productCollection->remove($product2);

        //-- Check if collection does not contains product 2 but does contain the others
        $collection = $productCollection->getAll();
        self::assertEquals(2, count($collection));
        self::assertEquals($product1, $collection[(string) $product1->getUuid()]);
        self::assertEquals($product3, $productCollection->get($product3->getUuid()));
        self::assertFalse(array_key_exists((string) $product2->getUuid(), $collection));
    }

    private function getProductWithUuid(): Product
    {
        return Product::new(
            'name',
            'description',
            new CategoryCollection(),
            new ImageCollection(),
            new VariantCollection()
        );
    }
}
