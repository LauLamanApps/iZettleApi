<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Api\Product;

use LauLamanApps\iZettleApi\API\Product\Variant;
use LauLamanApps\iZettleApi\API\Product\VariantCollection;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/** * @small */
final class VariantCollectionTest extends TestCase
{
    /** @test */
    public function variantCollection()
    {
        $variant1 = $this->getVariantWithUuid();
        $variant2 = $this->getVariantWithUuid();
        $variant3 = $this->getVariantWithUuid();

        $variantCollection =  new VariantCollection([$variant1, $variant2, $variant3]);
        $variantCollection->add($variant3);// add variant 3 again it should only end up once in the collection

        //-- Check if collection contains all 3 variants
        $collection = $variantCollection->getAll();
        self::assertEquals(3, count($collection));
        self::assertEquals($variant1, $collection[(string) $variant1->getUuid()]);
        self::assertEquals($variant2, $collection[(string) $variant2->getUuid()]);
        self::assertEquals($variant3, $collection[(string) $variant3->getUuid()]);


        $variantCollection->remove($variant2);

        //-- Check if collection does not contains variant 2 but does contain the others
        $collection = $variantCollection->getAll();
        self::assertEquals(2, count($collection));
        self::assertEquals($variant1, $collection[(string) $variant1->getUuid()]);
        self::assertEquals($variant3, $variantCollection->get($variant3->getUuid()));
        self::assertFalse(array_key_exists((string) $variant2->getUuid(), $collection));
    }

    private function getVariantWithUuid()
    {
        return Variant::new(
            null,
            null,
            null,
            null,
            1,
            null,
            Money::EUR(0),
            null,
            0.0
        );
    }
}
