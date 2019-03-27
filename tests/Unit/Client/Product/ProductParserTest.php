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
        $categoryBuilderMock = Mockery::mock(CategoryBuilderInterface::class);
        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $variantBuilderMock = Mockery::mock(VariantBuilderInterface::class);

        foreach ($data as $product) {
            $categoryBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['categories']))->once()->andReturn(new CategoryCollection());
            $imageBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['imageLookupKeys']))->once()->andReturn(new ImageCollection());
            $variantBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['variants']))->once()->andReturn(new VariantCollection());
        }

        $builder = new ProductBuilder($categoryBuilderMock, $imageBuilderMock, $variantBuilderMock);
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
            self::assertSame($data[$index]['vatPercentage'], $product->getVat()->getPercentage());
        }
    }

    /**
     * @test
     */
    public function buildSingleFromJson(): void
    {
        $data = $this->loadJsonFromFile('single-product.json');
        $productArray = json_decode($data, true)[0];
        $json = json_encode($productArray);

        $categoryBuilderMock = Mockery::mock(CategoryBuilderInterface::class);
        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $variantBuilderMock = Mockery::mock(VariantBuilderInterface::class);

        $categoryBuilderMock->shouldReceive('buildFromArray')
            ->with(($productArray['categories']))->once()->andReturn(new CategoryCollection());
        $imageBuilderMock->shouldReceive('buildFromArray')
            ->with(($productArray['imageLookupKeys']))->once()->andReturn(new ImageCollection());
        $variantBuilderMock->shouldReceive('buildFromArray')
            ->with(($productArray['variants']))->once()->andReturn(new VariantCollection());

        $builder = new ProductBuilder($categoryBuilderMock, $imageBuilderMock, $variantBuilderMock);
        $product = $builder->buildSingleFromJson($json);

            self::assertInstanceOf(Product::class, $product);
            self::assertSame($productArray['uuid'], (string) $product->getUuid());
            self::assertInstanceOf(CategoryCollection::class, $product->getCategories());
            self::assertSame($productArray['name'], $product->getName());
            self::assertSame($productArray['description'], $product->getDescription());
            self::assertInstanceOf(ImageCollection::class, $product->getImageLookupKeys());
            self::assertInstanceOf(VariantCollection::class, $product->getVariants());
            self::assertSame($productArray['externalReference'], $product->getExternalReference());
            self::assertSame($productArray['etag'], $product->getEtag());
            self::assertEquals(new DateTime($productArray['updated']), $product->getUpdatedAt());
            self::assertSame($productArray['updatedBy'], (string) $product->getUpdatedBy());
            self::assertEquals(new DateTime($productArray['created']), $product->getCreatedAt());
            self::assertSame((float)$productArray['vatPercentage'], $product->getVatPercentage());
    }

    /**
     * @test
     * @dataProvider getProductArrayData
     */
    public function buildFromArray($data): void
    {
        $categoryBuilderMock = Mockery::mock(CategoryBuilderInterface::class);
        $imageBuilderMock = Mockery::mock(ImageBuilderInterface::class);
        $variantBuilderMock = Mockery::mock(VariantBuilderInterface::class);

        foreach ($data as $product) {
            $categoryBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['categories']))->once()->andReturn(new CategoryCollection());
            $imageBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['imageLookupKeys']))->once()->andReturn(new ImageCollection());
            $variantBuilderMock->shouldReceive('buildFromArray')
                ->with(($product['variants']))->once()->andReturn(new VariantCollection());
        }

        $builder = new ProductBuilder($categoryBuilderMock, $imageBuilderMock, $variantBuilderMock);
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
            self::assertSame($data[$index]['vatPercentage'], $product->getVat()->getPercentage());
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

    private function getDataFromFile(string $filename): array
    {
        $singleProductJson = $this->loadJsonFromFile($filename);
        $singleProductArray = json_decode($singleProductJson, true);

        return [$singleProductJson, $singleProductArray];
    }

    private function loadJsonFromFile(string $filename): string
    {
        return file_get_contents(dirname(__FILE__) . '/json-files/' . $filename);
    }
}
