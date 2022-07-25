<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Product;

use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class CategoryCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function categoryCollection(): void
    {
        $category1 = $this->getCategoryWithUuid((string) Uuid::uuid1());
        $category2 = $this->getCategoryWithUuid((string) Uuid::uuid1());
        $category3 = $this->getCategoryWithUuid((string) Uuid::uuid1());

        $categoryCollection =  new CategoryCollection([$category1, $category2, $category3]);
        $categoryCollection->add($category3);// add category 3 again it should only end up once in the collection

        //-- Check if collection contains all 3 categories
        $collection = $categoryCollection->getAll();
        self::assertEquals(3, count($collection));
        self::assertEquals($category1, $collection[(string) $category1->getUuid()]);
        self::assertEquals($category2, $collection[(string) $category2->getUuid()]);
        self::assertEquals($category3, $collection[(string) $category3->getUuid()]);

        $categoryCollection->remove($category2);

        //-- Check if collection does not contains category 2 but does contain the others
        $collection = $categoryCollection->getAll();
        self::assertEquals(2, count($collection));
        self::assertEquals($category1, $collection[(string) $category1->getUuid()]);
        self::assertEquals($category3, $categoryCollection->get($category3->getUuid()));
        self::assertFalse(array_key_exists((string) $category2->getUuid(), $collection));
    }

    private function getCategoryWithUuid($name)
    {
        return Category::new($name);
    }
}
