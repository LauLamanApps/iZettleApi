<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit;

use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\Library;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Product\Variant;
use LauLamanApps\IzettleApi\API\Product\VariantCollection;
use LauLamanApps\IzettleApi\ProductClient;
use Money\Money;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class ProductClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getCategories()
    {
        $method = 'get';
        $url = sprintf(ProductClient::GET_CATEGORIES, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $categories = $productClient->getCategories();
        self::assertTrue(is_array($categories));
    }

    /**
     * @test
     */
    public function createCategory()
    {
        $category = Category::new('name');
        $method = 'post';
        $url = sprintf(ProductClient::POST_CATEGORY, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $category->getCreateData(),
        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $productClient->createCategory($category);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function getDiscounts()
    {
        $method = 'get';
        $url = sprintf(ProductClient::GET_DISCOUNTS, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $discounts = $productClient->getDiscounts();
        self::assertTrue(is_array($discounts));
    }

    /**
     * @test
     */
    public function createDiscount()
    {
        $discount = $this->getDiscount();
        $method = 'post';
        $url = sprintf(ProductClient::POST_DISCOUNT, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $discount->getCreateData(),
        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $productClient->createDiscount($discount);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function deleteDiscount()
    {
        $discount = $this->getDiscount();
        $method = 'delete';
        $url = sprintf(
            ProductClient::DELETE_DISCOUNT,
            self::DEFAULT_ORGANIZATION_UUID,
            (string) $discount->getUuid()
        );
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
            ],
        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $productClient->deleteDiscount($discount);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function getLibrary()
    {
        $method = 'get';
        $url = sprintf(ProductClient::GET_LIBRARY, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $return = [
            'fromEventLogUuid' => Uuid::uuid1(),
            'untilEventLogUuid' => Uuid::uuid1(),
            'products' => [],
            'discounts' => [],
            'deletedProducts' => [],
            'deletedDiscounts' => [],
        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $library = $productClient->getLibrary();
        self::assertInstanceOf(Library::class, $library);
    }

    /**
     * @test
     */
    public function getProducts()
    {
        $method = 'get';
        $url = sprintf(ProductClient::GET_PRODUCTS, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $return = [

        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $products = $productClient->getProducts();
        self::assertTrue(is_array($products));
    }

    /**
     * @test
     */
    public function createProduct()
    {
        $product = $this->getProduct();
        $method = 'post';
        $url = sprintf(ProductClient::POST_PRODUCT, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $product->getCreateData(),
        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $productClient->createProduct($product);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function deleteProduct()
    {
        $product = $this->getProduct();
        $method = 'delete';
        $url = sprintf(
            ProductClient::DELETE_PRODUCT,
            self::DEFAULT_ORGANIZATION_UUID,
            (string) $product->getUuid()
        );
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
            ],
        ];

        $productClient = new ProductClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $productClient->deleteProduct($product);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    private function getDiscount(): Discount
    {
        return Discount::new('name', 'description', new ImageCollection());
    }

    private function getProduct(): Product
    {
        return Product::new(
            'name',
            'description',
            new CategoryCollection(),
            new ImageCollection(),
            new VariantCollection([ Variant::new(
                null,
                null,
                null,
                null,
                1,
                null,
                Money::EUR(0),
                null,
                21
            )])
        );
    }
}
