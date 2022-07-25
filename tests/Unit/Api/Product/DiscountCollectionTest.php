<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Prozduct;

use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\DiscountCollection;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class DiscountCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function discountCollection(): void
    {
        $discount1 = $this->getDiscountWithUuid();
        $discount2 = $this->getDiscountWithUuid();
        $discount3 = $this->getDiscountWithUuid();

        $discountCollection = new DiscountCollection([$discount1, $discount2, $discount3]);
        $discountCollection->add($discount3);// add discount 3 again it should only end up once in the collection

        //-- Check if collection contains all 3 discounts
        $collection = $discountCollection->getAll();
        $this->assertEquals(3, count($collection));
        $this->assertEquals($discount1, $collection[(string) $discount1->getUuid()]);
        $this->assertEquals($discount2, $collection[(string) $discount2->getUuid()]);
        $this->assertEquals($discount3, $collection[(string) $discount3->getUuid()]);

        $discountCollection->remove($discount2);

        //-- Check if collection does not contains discount 2 but does contain the others
        $collection = $discountCollection->getAll();
        $this->assertEquals(2, count($collection));
        $this->assertEquals($discount1, $collection[(string) $discount1->getUuid()]);
        $this->assertEquals($discount3, $discountCollection->get($discount3->getUuid()));
        $this->assertFalse(array_key_exists((string) $discount2->getUuid(), $collection));
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
