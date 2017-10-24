<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\Library;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Product\Variant;
use LauLamanApps\IzettleApi\API\Product\VariantCollection;
use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\Client\AccessToken;
use LauLamanApps\IzettleApi\IzettleClient;
use Mockery;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class IzettleClientTest extends TestCase
{
    const ACCESS_TOKEN = 'access-token';
    const DEFAULT_ORGANIZATION_UUID = 'self';

    /**
     * @test
     */
    public function setOrganizationUuid()
    {
        $newOrganizationUuid = 123456;
        $IzettleClient = new IzettleClient(new GuzzleClient(), $this->getAccessToken());
        self::assertAttributeEquals(self::DEFAULT_ORGANIZATION_UUID, 'organizationUuid', $IzettleClient);

        $IzettleClient->setOrganizationUuid($newOrganizationUuid);

        self::assertAttributeEquals($newOrganizationUuid, 'organizationUuid', $IzettleClient);
    }

    /**
     * @test
     */
    public function getCategories()
    {
        $method = 'get';
        $url = sprintf(IzettleClient::PRODUCT_CATEGORY_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $categories = $IzettleClient->getCategories();
        self::assertTrue(is_array($categories));
    }

    /**
     * @test
     */
    public function createCategory()
    {
        $category = Category::new('name');
        $method = 'post';
        $url = sprintf(IzettleClient::PRODUCT_CATEGORY_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $category->getCreateData(),
        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $IzettleClient->createCategory($category);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function getDiscounts()
    {
        $method = 'get';
        $url = sprintf(IzettleClient::PRODUCT_DISCOUNT_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $discounts = $IzettleClient->getDiscounts();
        self::assertTrue(is_array($discounts));
    }

    /**
     * @test
     */
    public function createDiscount()
    {
        $discount = $this->getDiscount();
        $method = 'post';
        $url = sprintf(IzettleClient::PRODUCT_DISCOUNT_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $discount->getCreateData(),
        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $IzettleClient->createDiscount($discount);

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
            IzettleClient::PRODUCT_DISCOUNT_SINGLE,
            self::DEFAULT_ORGANIZATION_UUID,
            (string) $discount->getUuid()
        );
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
            ],
        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $IzettleClient->deleteDiscount($discount);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function getLibrary()
    {
        $method = 'get';
        $url = sprintf(IzettleClient::PRODUCT_LIBRARY, self::DEFAULT_ORGANIZATION_UUID);
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

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $library = $IzettleClient->getLibrary();
        self::assertInstanceOf(Library::class, $library);
    }

    /**
     * @test
     */
    public function getProducts()
    {
        $method = 'get';
        $url = sprintf(IzettleClient::PRODUCT_PRODUCTS_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $return = [

        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $products = $IzettleClient->getProducts();
        self::assertTrue(is_array($products));
    }

    /**
     * @test
     */
    public function createProduct()
    {
        $product = $this->getProduct();
        $method = 'post';
        $url = sprintf(IzettleClient::PRODUCT_PRODUCTS_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $product->getCreateData(),
        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $IzettleClient->createProduct($product);

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
            IzettleClient::PRODUCT_PRODUCTS_SINGLE,
            self::DEFAULT_ORGANIZATION_UUID,
            (string) $product->getUuid()
        );
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
            ],
        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $IzettleClient->deleteProduct($product);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function getPurchaseHistory()
    {
        $method = 'get';
        $url = sprintf(IzettleClient::PURCHASE_HISTORY_URL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $return = [
            'firstPurchaseHash' => Uuid::uuid1(),
            'lastPurchaseHash' => Uuid::uuid1(),
            'purchases' => [],
        ];

        $IzettleClient = new IzettleClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $purchaseHistory = $IzettleClient->getPurchaseHistory();
        self::assertInstanceOf(PurchaseHistory::class, $purchaseHistory);
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Exceptions\AccessTokenExpiredException
     */
    public function validateAccessToken()
    {
        $invalidAccessToken =  new AccessToken('', new DateTime('-1 day'), '');

        new IzettleClient(new GuzzleClient(), $invalidAccessToken);
    }

    private function getAccessToken() : AccessToken
    {
        return new AccessToken(self::ACCESS_TOKEN, new DateTime('+ 1 day'), '');
    }

    private function getGuzzleClient(
        string $method,
        string $url,
        array $options,
        ?array $return = []
    ): GuzzleClient {
        $guzzleResponseMock = Mockery::mock(ResponseInterface::class);
        $guzzleResponseMock->shouldReceive('getBody')->andReturnSelf();
        $guzzleResponseMock->shouldReceive('getContents')->andReturn(json_encode($return));

        $guzzleClientMock = Mockery::mock(GuzzleClient::class);
        $guzzleClientMock->shouldReceive($method)->withArgs([$url, $options])->andReturn($guzzleResponseMock);

        return $guzzleClientMock;
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
