<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Product\VariantCollection;
use LauLamanApps\IzettleApi\Client\Product\CategoryBuilderInterface;
use LauLamanApps\IzettleApi\Client\Product\ProductBuilder;
use LauLamanApps\IzettleApi\Client\Product\VariantBuilderInterface;
use LauLamanApps\IzettleApi\Client\Universal\ImageBuilderInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class ProductBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider getProductJsonData
     */
    public function buildFromJson($json, $data): void
    {
        $categoryBuilderMock =  Mockery::mock(CategoryBuilderInterface::class);
        $imageBuilderMock =  Mockery::mock(ImageBuilderInterface::class);
        $variantBuilderMock =  Mockery::mock(VariantBuilderInterface::class);

        foreach ($data as $product) {
            $categoryBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['categories']))->once()->andReturn(new CategoryCollection());
            $imageBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['imageLookupKeys']))->once()->andReturn(new ImageCollection());
            $variantBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['variants']))->once()->andReturn(new VariantCollection());
        }

        $builder =  new ProductBuilder($categoryBuilderMock, $imageBuilderMock, $variantBuilderMock);
        $products = $builder->buildFromJson($json);

        foreach ($products as $index => $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertSame($data[$index]['uuid'], (string) $product->getUuid());
            $this->assertInstanceOf(CategoryCollection::class, $product->getCategories());
            $this->assertSame($data[$index]['name'], $product->getName());
            $this->assertSame($data[$index]['description'], $product->getDescription());
            $this->assertInstanceOf(ImageCollection::class, $product->getImageLookupKeys());
            $this->assertInstanceOf(VariantCollection::class, $product->getVariants());
            $this->assertSame($data[$index]['externalReference'], $product->getExternalReference());
            $this->assertSame($data[$index]['etag'], $product->getEtag());
            $this->assertEquals(new DateTime($data[$index]['updated']), $product->getUpdatedAt());
            $this->assertSame($data[$index]['updatedBy'], (string) $product->getUpdatedBy());
            $this->assertEquals(new DateTime($data[$index]['created']), $product->getCreatedAt());
            $this->assertSame((float)$data[$index]['vatPercentage'], $product->getVatPercentage());
        }
    }

    /**
     * @test
     * @dataProvider getProductArrayData
     */
    public function buildFromArray($data): void
    {
        $categoryBuilderMock =  Mockery::mock(CategoryBuilderInterface::class);
        $imageBuilderMock =  Mockery::mock(ImageBuilderInterface::class);
        $variantBuilderMock =  Mockery::mock(VariantBuilderInterface::class);

        foreach ($data as $product) {
            $categoryBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['categories']))->once()->andReturn(new CategoryCollection());
            $imageBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['imageLookupKeys']))->once()->andReturn(new ImageCollection());
            $variantBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['variants']))->once()->andReturn(new VariantCollection());
        }

        $builder =  new ProductBuilder($categoryBuilderMock, $imageBuilderMock, $variantBuilderMock);
        $products = $builder->buildFromArray($data);

        $index = 0;
        foreach ($products->getAll() as $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertSame($data[$index]['uuid'], (string) $product->getUuid());
            $this->assertInstanceOf(CategoryCollection::class, $product->getCategories());
            $this->assertSame($data[$index]['name'], $product->getName());
            $this->assertSame($data[$index]['description'], $product->getDescription());
            $this->assertInstanceOf(ImageCollection::class, $product->getImageLookupKeys());
            $this->assertInstanceOf(VariantCollection::class, $product->getVariants());
            $this->assertSame($data[$index]['externalReference'], $product->getExternalReference());
            $this->assertSame($data[$index]['etag'], $product->getEtag());
            $this->assertEquals(new DateTime($data[$index]['updated']), $product->getUpdatedAt());
            $this->assertSame($data[$index]['updatedBy'], (string) $product->getUpdatedBy());
            $this->assertEquals(new DateTime($data[$index]['created']), $product->getCreatedAt());
            $this->assertSame((float)$data[$index]['vatPercentage'], $product->getVatPercentage());
            $index++;
        }
    }

    public function getProductJsonData(): array
    {
        return [
            'single' => $this->getDataFromFile('single-product.json'),
            'multiple' => $this->getDataFromFile('multiple-product.json'),
        ];
    }

    public function getProductArrayData(): array
    {
        return [
            'single' => [$this->getDataFromFile('single-product.json')[1]],
            'multiple' => [$this->getDataFromFile('multiple-product.json')[1]],
        ];
    }

    private function getDataFromFile($filename): array
    {
        $singleProductJson = file_get_contents(__DIR__ . '/json-files/' . $filename);
        $singleProductArray = json_decode($singleProductJson, true);

        return [$singleProductJson, $singleProductArray];
    }
}
