<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Product;

use LauLamanApps\IzettleApi\API\Product\DiscountCollection;
use LauLamanApps\IzettleApi\API\Product\ProductCollection;
use LauLamanApps\IzettleApi\Client\Product\DiscountBuilderInterface;
use LauLamanApps\IzettleApi\Client\Product\LibraryBuilder;
use LauLamanApps\IzettleApi\Client\Product\ProductBuilderInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @small
 */
final class LibraryBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function createFromResponse(): void
    {
        $json = file_get_contents(__DIR__ . '/json-files/library.json');
        $data = json_decode($json, true);

        $productBuilderMock =  Mockery::mock(ProductBuilderInterface::class);
        $productBuilderMock->shouldReceive('buildFromArray')->with($data['products'])
            ->once()->andReturn(new ProductCollection());
        $productBuilderMock->shouldReceive('buildFromArray')->with($data['deletedProducts'])
            ->once()->andReturn(new ProductCollection());

        $discountBuilderMock =  Mockery::mock(DiscountBuilderInterface::class);
        $discountBuilderMock->shouldReceive('buildFromArray')->with($data['discounts'])
            ->once()->andReturn(new DiscountCollection());
        $discountBuilderMock->shouldReceive('buildFromArray')->with($data['deletedDiscounts'])
            ->once()->andReturn(new DiscountCollection());

        $builder =  new LibraryBuilder($productBuilderMock, $discountBuilderMock);

        $library = $builder->buildFromJson($json);

        $this->assertInstanceOf(UuidInterface::class, $library->getFromEventLogUuid());
        $this->assertInstanceOf(UuidInterface::class, $library->getUntilEventLogUuid());

        $this->assertInstanceOf(ProductCollection::class, $library->getProducts());
        $this->assertInstanceOf(DiscountCollection::class, $library->getDiscounts());
        $this->assertInstanceOf(ProductCollection::class, $library->getDeletedProducts());
        $this->assertInstanceOf(DiscountCollection::class, $library->getDeletedDiscounts());
    }

    /**
     * @test
     */
    public function createFromResponseWithNullfromEventLogUuid(): void
    {
        $json = file_get_contents(__DIR__ . '/json-files/library-null-fromEventLogUuid.json');
        $data = json_decode($json, true);

        $productBuilderMock =  Mockery::mock(ProductBuilderInterface::class);
        $productBuilderMock->shouldReceive('buildFromArray')->with($data['products'])
            ->once()->andReturn(new ProductCollection());
        $productBuilderMock->shouldReceive('buildFromArray')->with($data['deletedProducts'])
            ->once()->andReturn(new ProductCollection());

        $discountBuilderMock =  Mockery::mock(DiscountBuilderInterface::class);
        $discountBuilderMock->shouldReceive('buildFromArray')->with($data['discounts'])
            ->once()->andReturn(new DiscountCollection());
        $discountBuilderMock->shouldReceive('buildFromArray')->with($data['deletedDiscounts'])
            ->once()->andReturn(new DiscountCollection());

        $builder =  new LibraryBuilder($productBuilderMock, $discountBuilderMock);

        $library = $builder->buildFromJson($json);

        $this->assertNull($library->getFromEventLogUuid());
        $this->assertInstanceOf(UuidInterface::class, $library->getUntilEventLogUuid());

        $this->assertInstanceOf(ProductCollection::class, $library->getProducts());
        $this->assertInstanceOf(DiscountCollection::class, $library->getDiscounts());
        $this->assertInstanceOf(ProductCollection::class, $library->getDeletedProducts());
        $this->assertInstanceOf(DiscountCollection::class, $library->getDeletedDiscounts());
    }
}
