<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Product;

use LauLamanApps\IzettleApi\API\Product\Variant;
use LauLamanApps\IzettleApi\API\Product\VariantCollection;
use LauLamanApps\IzettleApi\Client\Product\VariantBuilder;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class VariantsBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider getVariantArrayData
     */
    public function buildFromArray(array $data): void
    {
        $builder = new VariantBuilder();
        $variantCollection = $builder->buildFromArray($data);

        $this->assertInstanceOf(VariantCollection::class, $variantCollection);

        $i = 0;// we cannot use the array key here
        foreach ($variantCollection->getAll() as $variant) {
            $this->assertInstanceOf(Variant::class, $variant);
            $this->assertInstanceOf(Money::class, $variant->getPrice());
            $this->assertSame($data[$i]['uuid'], (string) $variant->getUuid());
            $this->assertSame($data[$i]['name'], $variant->getName());
            $this->assertSame($data[$i]['description'], $variant->getDescription());
            $this->assertSame($data[$i]['sku'], $variant->getSku());
            $this->assertSame($data[$i]['barcode'], $variant->getBarcode());
            $this->assertSame((int) $data[$i]['defaultQuantity'], $variant->getDefaultQuantity());
            $this->assertSame($data[$i]['unitName'], $variant->getUnitName());
            $this->assertSame((string) $data[$i]['price']['amount'], $variant->getPrice()->getAmount());
            $this->assertSame($data[$i]['price']['currencyId'], $variant->getPrice()->getCurrency()->getCode());
            $this->assertSame((float) $data[$i]['vatPercentage'], $variant->getVatPercentage());

            if (is_null($data[$i]['costPrice'])) {
                $this->assertSame($data[$i]['costPrice'], $variant->getCostPrice());
            } else {
                $this->assertInstanceOf(Money::class, $variant->getCostPrice());
                $this->assertSame((string) $data[$i]['costPrice']['amount'], $variant->getCostPrice()->getAmount());
                $this->assertSame($data[$i]['costPrice']['currencyId'], $variant->getCostPrice()->getCurrency()->getCode());
            }

            $i++;
        }
    }

    public function getVariantArrayData(): array
    {
        return [
            'single variant' => [
                [
                    [
                        "uuid" => (string) Uuid::uuid1(),
                        "name" => "a variant name",
                        "description" => 'the variant description',
                        "sku" => null,
                        "barcode" => null,
                        "defaultQuantity" => "1",
                        "unitName" => null,
                        "price" => [
                            "amount" => 1000,
                            "currencyId" => "EUR",
                        ],
                        "costPrice" => null,
                        "vatPercentage" => "6.0",
                    ],
                ],
            ],
            'multiple variant' =>[
                [
                    [
                        "uuid" => (string) Uuid::uuid1(),
                        "name" => "Variant A",
                        "description" => null,
                        "sku" => 'Prod-VarA',
                        "barcode" => '9782123456803',
                        "defaultQuantity" => "1",
                        "unitName" => null,
                        "price" => [
                            "amount" => 1200,
                            "currencyId" => "EUR",
                        ],
                        "costPrice" => [
                            "amount" => 700,
                            "currencyId" => "EUR",
                        ],
                        "vatPercentage" => "6.0",
                    ],
                ],
                [
                    [
                        "uuid" => (string) Uuid::uuid1(),
                        "name" => "Variant B",
                        "description" => null,
                        "sku" => 'Prod-VarB',
                        "barcode" => null,
                        "defaultQuantity" => "1",
                        "unitName" => null,
                        "price" => [
                            "amount" => 1500,
                            "currencyId" => "EUR",
                        ],
                        "costPrice" => [
                            "amount" => 900,
                            "currencyId" => "EUR",
                        ],
                        "vatPercentage" => "6.0",
                    ],
                ],
            ],
        ];
    }
}
