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
    public function buildFromJson($json, $data)
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
            self::assertInstanceOf(Product::class, $product);
            self::assertSame($data[$index]['uuid'], (string) $product->getUuid());
            self::assertInstanceOf(CategoryCollection::class, $product->getCategories());
            self::assertSame($data[$index]['name'], $product->getName());
            self::assertSame($data[$index]['description'], $product->getDescription());
            self::assertInstanceOf(ImageCollection::class, $product->getImageLookupKeys());
            self::assertInstanceOf(VariantCollection::class, $product->getVariants());
            self::assertSame($data[$index]['externalReference'], $product->getExternalReference());
            self::assertSame($data[$index]['etag'], $product->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $product->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $product->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $product->getCreatedAt());
            self::assertSame((float)$data[$index]['vatPercentage'], $product->getVatPercentage());
        }
    }

    /**
     * @test
     * @dataProvider getProductArrayData
     */
    public function buildFromArray($data)
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
            self::assertInstanceOf(Product::class, $product);
            self::assertSame($data[$index]['uuid'], (string) $product->getUuid());
            self::assertInstanceOf(CategoryCollection::class, $product->getCategories());
            self::assertSame($data[$index]['name'], $product->getName());
            self::assertSame($data[$index]['description'], $product->getDescription());
            self::assertInstanceOf(ImageCollection::class, $product->getImageLookupKeys());
            self::assertInstanceOf(VariantCollection::class, $product->getVariants());
            self::assertSame($data[$index]['externalReference'], $product->getExternalReference());
            self::assertSame($data[$index]['etag'], $product->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $product->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $product->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $product->getCreatedAt());
            self::assertSame((float)$data[$index]['vatPercentage'], $product->getVatPercentage());
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
        $singleProductJson = file_get_contents(dirname(__FILE__) . '/json-files/' . $filename);
        $singleProductArray = json_decode($singleProductJson, true);

        return [$singleProductJson, $singleProductArray];
    }
}
