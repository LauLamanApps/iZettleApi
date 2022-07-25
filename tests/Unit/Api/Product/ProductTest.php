<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Product;

use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Product\Variant;
use LauLamanApps\IzettleApi\API\Product\VariantCollection;
use LauLamanApps\IzettleApi\Client\Exception\CantCreateProductException;
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

        $createData = json_decode($product->getPostBodyData(), true);
        $this->assertTrue(Uuid::isValid($createData['uuid']));
        $this->assertSame($name, $createData['name']);
        $this->assertSame($description, $createData['description']);
        $this->assertSame($imageCollection->getCreateDataArray(), $createData['imageLookupKeys']);
        $this->assertSame($externalReference, $createData['externalReference']);
    }

    /**
     * @test
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

        $this->expectException(CantCreateProductException::class);
        $product->getPostBodyData();
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
                    ),
                ]),
                'externalReference1',
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
                    ),
                ]),
                'externalReference2',
            ],
        ];
    }
}
