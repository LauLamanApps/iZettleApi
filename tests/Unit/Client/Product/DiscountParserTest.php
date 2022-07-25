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
    public function buildFromJsonSingle(): void
    {
        $json = file_get_contents(__DIR__ . '/json-files/single-discount.json');
        $data = json_decode($json, true)[0];

        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data['imageLookupKeys'])->once()->andReturn(new ImageCollection());

        $builder = new DiscountBuilder($imageBuilderMock);
        $discount = $builder->buildFromJson($json)[0];

        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertSame($data['uuid'], (string) $discount->getUuid());
        $this->assertSame($data['name'], $discount->getName());
        $this->assertSame($data['description'], $discount->getDescription());
        $this->assertInstanceOf(ImageCollection::class, $discount->getImageCollection());
        $this->assertSame($data['externalReference'], $discount->getExternalReference());
        $this->assertSame($data['etag'], $discount->getEtag());
        $this->assertEquals(new DateTime($data['updated']), $discount->getUpdatedAt());
        $this->assertSame($data['updatedBy'], (string) $discount->getUpdatedBy());
        $this->assertEquals(new DateTime($data['created']), $discount->getCreatedAt());

        if ($data['amount']) {
            $this->assertInstanceOf(Money::class, $discount->getAmount());
            $this->assertSame((string) $data['amount']['amount'], $discount->getAmount()->getAmount());
            $this->assertSame($data['amount']['currencyId'], $discount->getAmount()->getCurrency()->getCode());
        } else {
            $this->assertSame((float) $data['percentage'], $discount->getPercentage());
        }
    }

    /**
     * @test
     */
    public function buildFromJsonMultiple(): void
    {
        $json = file_get_contents(__DIR__ . '/json-files/multiple-discount.json');
        $data = json_decode($json, true);

        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data[0]['imageLookupKeys'])->once()->andReturn(new ImageCollection());
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data[1]['imageLookupKeys'])->once()->andReturn(new ImageCollection());

        $builder = new DiscountBuilder($imageBuilderMock);
        $discounts = $builder->buildFromJson($json);

        foreach ($discounts as $index => $discount) {
            $this->assertInstanceOf(Discount::class, $discount);
            $this->assertSame($data[$index]['uuid'], (string) $discount->getUuid());
            $this->assertSame($data[$index]['name'], $discount->getName());
            $this->assertSame($data[$index]['description'], $discount->getDescription());
            $this->assertInstanceOf(ImageCollection::class, $discount->getImageCollection());
            $this->assertSame($data[$index]['externalReference'], $discount->getExternalReference());
            $this->assertSame($data[$index]['etag'], $discount->getEtag());
            $this->assertEquals(new DateTime($data[$index]['updated']), $discount->getUpdatedAt());
            $this->assertSame($data[$index]['updatedBy'], (string) $discount->getUpdatedBy());
            $this->assertEquals(new DateTime($data[$index]['created']), $discount->getCreatedAt());

            if ($data[$index]['amount']) {
                $this->assertInstanceOf(Money::class, $discount->getAmount());
                $this->assertSame((string) $data[$index]['amount']['amount'], $discount->getAmount()->getAmount());
                $this->assertSame($data[$index]['amount']['currencyId'], $discount->getAmount()->getCurrency()->getCode());
            } else {
                $this->assertSame((float) $data[$index]['percentage'], $discount->getPercentage());
            }
        }
    }

    /**
     * @test
     */
    public function buildFromArray(): void
    {
        $json = file_get_contents(__DIR__ . '/json-files/multiple-discount.json');
        $data = json_decode($json, true);

        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data[0]['imageLookupKeys'])->once()->andReturn(new ImageCollection());
        $imageBuilderMock->shouldReceive('buildFromArray')->with($data[1]['imageLookupKeys'])->once()->andReturn(new ImageCollection());

        $builder = new DiscountBuilder($imageBuilderMock);
        $discounts = $builder->buildFromArray($data);

        $this->assertInstanceOf(DiscountCollection::class, $discounts);

        foreach ($discounts->getAll() as $discount) {
            $this->assertInstanceOf(Discount::class, $discount);
        }
    }
}
