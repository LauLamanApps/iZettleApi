<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Product;

use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Product\Variant;
use LauLamanApps\IzettleApi\API\Product\VariantCollection;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/** * @small */
final class ProductTest extends TestCase
{
    /**
     * @test
     * @dataProvider getDiscountData
     */
    public function new(
        string $name,
        string $description,
        CategoryCollection $categories,
        ImageCollection $imageCollection,
        VariantCollection $variants,
        string $externalReference
    ): void {
        $product =  Product::new(
            $name,
            $description,
            $categories,
            $imageCollection,
            $variants,
            $externalReference
        );

        $createData = json_decode($product->getCreateData(), true);
        self::assertTrue(Uuid::isValid($createData['uuid']));
        self::assertSame($name, $createData['name']);
        self::assertSame($description, $createData['description']);
        self::assertSame($imageCollection->getCreateDataArray(), $createData['imageLookupKeys']);
        self::assertSame($externalReference, $createData['externalReference']);
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Exceptions\CantCreateProductException
     */
    public function cantCreateProductWithoutVariant(): void
    {
        $product =  Product::new(
            'name3',
            'description3',
            new CategoryCollection(),
            new ImageCollection(),
            new VariantCollection(),
            'externalReference3'
        );

        $product->getCreateData();
    }

    public function getDiscountData(): array
    {
        return [
            [
                'name1',
                'description1',
                new CategoryCollection(),
                new ImageCollection(),
                new VariantCollection([
                    Variant::new(
                        null,
                        null,
                        null,
                        null,
                        1,
                        null,
                        Money::EUR(500),
                        null,
                        12
                    )
                ]),
                'externalReference1'
            ],
            [
                'name2',
                'description2',
                new CategoryCollection([Category::new('')]),
                new ImageCollection(),
                new VariantCollection([
                    Variant::new(
                        null,
                        null,
                        null,
                        null,
                        1,
                        null,
                        Money::EUR(500),
                        Money::EUR(100),
                        12
                    )
                ]),
                'externalReference2'
            ]
        ];
    }
}
