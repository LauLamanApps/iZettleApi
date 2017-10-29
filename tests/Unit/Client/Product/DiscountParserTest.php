<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\DiscountCollection;
use LauLamanApps\IzettleApi\Client\Product\DiscountBuilder;
use LauLamanApps\IzettleApi\Client\Universal\ImageBuilderInterface;
use Mockery;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class DiscountBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function buildFromJsonSingle()
    {
        $json = file_get_contents(dirname(__FILE__) . '/json-files/single-discount.json');
        $data = json_decode($json, true)[0];

        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data['imageLookupKeys'])->once()->andReturn(new ImageCollection());

        $builder = new DiscountBuilder($imageBuilderMock);
        $discount = $builder->buildFromJson($json)[0];

        self::assertInstanceOf(Discount::class, $discount);
        self::assertSame($data['uuid'], (string) $discount->getUuid());
        self::assertSame($data['name'], $discount->getName());
        self::assertSame($data['description'], $discount->getDescription());
        self::assertInstanceOf(ImageCollection::class, $discount->getImageCollection());
        self::assertSame($data['externalReference'], $discount->getExternalReference());
        self::assertSame($data['etag'], $discount->getEtag());
        self::assertEquals(new DateTime($data['updated']), $discount->getUpdatedAt());
        self::assertSame($data['updatedBy'], (string) $discount->getUpdatedBy());
        self::assertEquals(new DateTime($data['created']), $discount->getCreatedAt());

        if ($data['amount']) {
            self::assertInstanceOf(Money::class, $discount->getAmount());
            self::assertSame((string) $data['amount']['amount'], $discount->getAmount()->getAmount());
            self::assertSame($data['amount']['currencyId'], $discount->getAmount()->getCurrency()->getCode());
        } else {
            self::assertSame((float) $data['percentage'], $discount->getPercentage());
        }
    }

    /**
     * @test
     */
    public function buildFromJsonMultiple()
    {
        $json = file_get_contents(dirname(__FILE__) . '/json-files/multiple-discount.json');
        $data = json_decode($json, true);

        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data[0]['imageLookupKeys'])->once()->andReturn(new ImageCollection());
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data[1]['imageLookupKeys'])->once()->andReturn(new ImageCollection());

        $builder = new DiscountBuilder($imageBuilderMock);
        $discounts = $builder->buildFromJson($json);

        foreach ($discounts as $index => $discount) {
            self::assertInstanceOf(Discount::class, $discount);
            self::assertSame($data[$index]['uuid'], (string) $discount->getUuid());
            self::assertSame($data[$index]['name'], $discount->getName());
            self::assertSame($data[$index]['description'], $discount->getDescription());
            self::assertInstanceOf(ImageCollection::class, $discount->getImageCollection());
            self::assertSame($data[$index]['externalReference'], $discount->getExternalReference());
            self::assertSame($data[$index]['etag'], $discount->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $discount->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $discount->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $discount->getCreatedAt());

            if ($data[$index]['amount']) {
                self::assertInstanceOf(Money::class, $discount->getAmount());
                self::assertSame((string) $data[$index]['amount']['amount'], $discount->getAmount()->getAmount());
                self::assertSame($data[$index]['amount']['currencyId'], $discount->getAmount()->getCurrency()->getCode());
            } else {
                self::assertSame((float) $data[$index]['percentage'], $discount->getPercentage());
            }
        }
    }

    /**
     * @test
     */
    public function buildFromArray()
    {
        $json = file_get_contents(dirname(__FILE__) . '/json-files/multiple-discount.json');
        $data = json_decode($json, true);

        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data[0]['imageLookupKeys'])->once()->andReturn(new ImageCollection());
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data[1]['imageLookupKeys'])->once()->andReturn(new ImageCollection());

        $builder = new DiscountBuilder($imageBuilderMock);
        $discounts = $builder->buildFromArray($data);

        self::assertInstanceOf(DiscountCollection::class, $discounts);

        foreach ($discounts->getAll() as $discount) {
            self::assertInstanceOf(Discount::class, $discount);
        }
    }
}
