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
        $json = file_get_contents(dirname(__FILE__) . '/files/ProductClient/getCategories.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getProductClient($iZettleClient);

        $categories = $purchaseClient->getCategories();

        foreach ($categories as $index => $category) {
            self::assertInstanceOf(Category::class, $category);
            self::assertSame($data[$index]['uuid'], (string) $category->getUuid());
            self::assertSame($data[$index]['name'], $category->getName());
            self::assertSame($data[$index]['etag'], $category->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $category->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $category->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $category->getCreatedAt());
        }
    }

    /**
     * @test
     */
    public function getDiscounts(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/files/ProductClient/getDiscounts.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getProductClient($iZettleClient);

        $discounts = $purchaseClient->getDiscounts();

        foreach ($discounts as $index => $discount) {
            self::assertInstanceOf(Discount::class, $discount);
            self::assertSame($data[$index]['uuid'], (string) $discount->getUuid());
            self::assertSame($data[$index]['name'], $discount->getName());
            self::assertSame($data[$index]['description'], $discount->getDescription());
            self::assertInstanceOf(ImageCollection::class, $discount->getImageCollection());
            foreach ($data[$index]['imageLookupKeys'] as $image) {
                self::assertTrue(array_key_exists($image, $discount->getImageCollection()->getAll()));
            }
            self::assertSame($data[$index]['amount'], ($discount->getAmount()) ? $discount->getAmount()->getAmount() : $discount->getAmount());
            self::assertSame((float) $data[$index]['percentage'], $discount->getPercentage());
            self::assertSame($data[$index]['externalReference'], $discount->getExternalReference());
            self::assertSame($data[$index]['etag'], $discount->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $discount->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $discount->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $discount->getCreatedAt());
        }
    }

    /**
     * @test
     */
    public function getLibrary(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/files/ProductClient/getLibrary.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getProductClient($iZettleClient);

        $library = $purchaseClient->getLibrary();

        self::assertSame($data['fromEventLogUuid'], (string) $library->getFromEventLogUuid());
        self::assertSame($data['untilEventLogUuid'], (string) $library->getUntilEventLogUuid());

        self::assertEquals(count($data['products']), count($library->getProducts()->getAll()));
        self::assertEquals(count($data['discounts']), count($library->getDiscounts()->getAll()));
        self::assertEquals(count($data['deletedProducts']), count($library->getDeletedProducts()->getAll()));
        self::assertEquals(count($data['deletedDiscounts']), count($library->getDeletedDiscounts()->getAll()));
    }

    /**
     * @test
     */
    public function getProducts(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/files/ProductClient/getProducts.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getProductClient($iZettleClient);

        $products = $purchaseClient->getProducts();

        foreach ($products as $index => $product) {
            self::assertSame($data[$index]['uuid'], (string) $product->getUuid());
//            self::assertSame($data[$index]['categories'], $product->getCategories());
            self::assertSame($data[$index]['name'], $product->getName());
            self::assertSame($data[$index]['description'], $product->getDescription());
//            self::assertSame($data[$index]['imageLookupKeys'], $product->getImageLookupKeys());
            self::assertSame($data[$index]['externalReference'], $product->getExternalReference());
            self::assertSame($data[$index]['etag'], $product->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $product->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $product->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $product->getCreatedAt());
//            self::assertSame($data[$index]['unitName'], $product->getUnitName());
            self::assertSame($data[$index]['vatPercentage'], $product->getVat()->getPercentage());
        }
    }
}
