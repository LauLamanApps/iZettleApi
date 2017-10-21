<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Api\Prozduct;

use LauLamanApps\iZettleApi\API\ImageCollection;
use LauLamanApps\iZettleApi\API\Product\Discount;
use LauLamanApps\iZettleApi\API\Product\DiscountCollection;
use PHPUnit\Framework\TestCase;

/** * @small */
final class DiscountCollectionTest extends TestCase
{
    /** @test */
    public function discountCollection()
    {
        $discount1 = $this->getDiscountWithUuid();
        $discount2 = $this->getDiscountWithUuid();
        $discount3 = $this->getDiscountWithUuid();

        $discountCollection = new DiscountCollection([$discount1, $discount2, $discount3]);
        $discountCollection->add($discount3);// add discount 3 again it should only end up once in the collection

        //-- Check if collection contains all 3 discounts
        $collection = $discountCollection->getAll();
        self::assertEquals(3, count($collection));
        self::assertEquals($discount1, $collection[(string) $discount1->getUuid()]);
        self::assertEquals($discount2, $collection[(string) $discount2->getUuid()]);
        self::assertEquals($discount3, $collection[(string) $discount3->getUuid()]);
        
        $discountCollection->remove($discount2);

        //-- Check if collection does not contains discount 2 but does contain the others
        $collection = $discountCollection->getAll();
        self::assertEquals(2, count($collection));
        self::assertEquals($discount1, $collection[(string) $discount1->getUuid()]);
        self::assertEquals($discount3, $discountCollection->get($discount3->getUuid()));
        self::assertFalse(array_key_exists((string) $discount2->getUuid(), $collection));
    }

    private function getDiscountWithUuid(): Discount
    {
        return Discount::new(
            'name',
            'description',
            new ImageCollection()
        );
    }
}
