<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Integration\Client;

use DateTime;
use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\IzettleClientFactory;

/**
 * @medium
 */
final class ProductClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getCategories(): void
    {
        $json = file_get_contents(__DIR__ . '/files/ProductClient/getCategories.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getProductClient($iZettleClient);

        $categories = $purchaseClient->getCategories();

        foreach ($categories as $index => $category) {
            $this->assertInstanceOf(Category::class, $category);
            $this->assertSame($data[$index]['uuid'], (string) $category->getUuid());
            $this->assertSame($data[$index]['name'], $category->getName());
            $this->assertSame($data[$index]['etag'], $category->getEtag());
            $this->assertEquals(new DateTime($data[$index]['updated']), $category->getUpdatedAt());
            $this->assertSame($data[$index]['updatedBy'], (string) $category->getUpdatedBy());
            $this->assertEquals(new DateTime($data[$index]['created']), $category->getCreatedAt());
        }
    }

    /**
     * @test
     */
    public function getDiscounts(): void
    {
        $json = file_get_contents(__DIR__ . '/files/ProductClient/getDiscounts.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getProductClient($iZettleClient);

        $discounts = $purchaseClient->getDiscounts();

        foreach ($discounts as $index => $discount) {
            $this->assertInstanceOf(Discount::class, $discount);
            $this->assertSame($data[$index]['uuid'], (string) $discount->getUuid());
            $this->assertSame($data[$index]['name'], $discount->getName());
            $this->assertSame($data[$index]['description'], $discount->getDescription());
            $this->assertInstanceOf(ImageCollection::class, $discount->getImageCollection());
            foreach ($data[$index]['imageLookupKeys'] as $image) {
                $this->assertTrue(array_key_exists($image, $discount->getImageCollection()->getAll()));
            }
            $this->assertSame($data[$index]['amount'], ($discount->getAmount()) ? $discount->getAmount()->getAmount() : $discount->getAmount());
            $this->assertSame((float) $data[$index]['percentage'], $discount->getPercentage());
            $this->assertSame($data[$index]['externalReference'], $discount->getExternalReference());
            $this->assertSame($data[$index]['etag'], $discount->getEtag());
            $this->assertEquals(new DateTime($data[$index]['updated']), $discount->getUpdatedAt());
            $this->assertSame($data[$index]['updatedBy'], (string) $discount->getUpdatedBy());
            $this->assertEquals(new DateTime($data[$index]['created']), $discount->getCreatedAt());
        }
    }

    /**
     * @test
     */
    public function getLibrary(): void
    {
        $json = file_get_contents(__DIR__ . '/files/ProductClient/getLibrary.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getProductClient($iZettleClient);

        $library = $purchaseClient->getLibrary();

        $this->assertSame($data['fromEventLogUuid'], (string) $library->getFromEventLogUuid());
        $this->assertSame($data['untilEventLogUuid'], (string) $library->getUntilEventLogUuid());

        $this->assertEquals(count($data['products']), count($library->getProducts()->getAll()));
        $this->assertEquals(count($data['discounts']), count($library->getDiscounts()->getAll()));
        $this->assertEquals(count($data['deletedProducts']), count($library->getDeletedProducts()->getAll()));
        $this->assertEquals(count($data['deletedDiscounts']), count($library->getDeletedDiscounts()->getAll()));
    }

    /**
     * @test
     */
    public function getProducts(): void
    {
        $json = file_get_contents(__DIR__ . '/files/ProductClient/getProducts.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getProductClient($iZettleClient);

        $products = $purchaseClient->getProducts();

        foreach ($products as $index => $product) {
            $this->assertSame($data[$index]['uuid'], (string) $product->getUuid());
//            $this->assertSame($data[$index]['categories'], $product->getCategories());
            $this->assertSame($data[$index]['name'], $product->getName());
            $this->assertSame($data[$index]['description'], $product->getDescription());
//            $this->assertSame($data[$index]['imageLookupKeys'], $product->getImageLookupKeys());
            $this->assertSame($data[$index]['externalReference'], $product->getExternalReference());
            $this->assertSame($data[$index]['etag'], $product->getEtag());
            $this->assertEquals(new DateTime($data[$index]['updated']), $product->getUpdatedAt());
            $this->assertSame($data[$index]['updatedBy'], (string) $product->getUpdatedBy());
            $this->assertEquals(new DateTime($data[$index]['created']), $product->getCreatedAt());
//            $this->assertSame($data[$index]['unitName'], $product->getUnitName());
            $this->assertSame((float) $data[$index]['vatPercentage'], $product->getVatPercentage());
        }
    }
}
