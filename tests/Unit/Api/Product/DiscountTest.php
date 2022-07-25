<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Product;

use LauLamanApps\IzettleApi\API\Image;
use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Discount;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/** * @small */
final class DiscountTest extends TestCase
{
    /**
     * @test
     * @dataProvider getDiscountData
     */
    public function new(
        string $name,
        string $description,
        ImageCollection $imageCollection,
        ?Money $amount,
        ?float $percentage,
        string $externalReference
    ): void {
        $discount =  Discount::new(
            $name,
            $description,
            $imageCollection,
            $amount,
            $percentage,
            $externalReference
        );

        $createData = json_decode($discount->getPostBodyData(), true);
        $this->assertTrue(Uuid::isValid($createData['uuid']));
        $this->assertSame($name, $createData['name']);
        $this->assertSame($description, $createData['description']);
        $this->assertSame($imageCollection->getCreateDataArray(), $createData['imageLookupKeys']);
        if ($amount) {
            $this->assertSame($amount->getAmount(), $createData['amount']['amount']);
            $this->assertSame($amount->getCurrency()->getCode(), $createData['amount']['currencyId']);
        } else {
            $this->assertSame((string) $percentage, $createData['percentage']);
        }
        $this->assertSame($externalReference, $createData['externalReference']);
        $this->assertSame($name, $createData['name']);
    }

    public function getDiscountData(): array
    {
        return [
            [
                'name1',
                'description1',
                new ImageCollection(),
                Money::EUR(100),
                null,
                'externalReference1',
            ],
            [
                'name2',
                'description2',
                new ImageCollection([new Image('file.jpg')]),
                null,
                10.0,
                'externalReference2',
            ],
        ];
    }
}
