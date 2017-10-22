<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Client;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use LauLamanApps\iZettleApi\API\ImageCollection;
use LauLamanApps\iZettleApi\API\Product\Category;
use LauLamanApps\iZettleApi\API\Product\CategoryCollection;
use LauLamanApps\iZettleApi\API\Product\Discount;
use LauLamanApps\iZettleApi\API\Product\Library;
use LauLamanApps\iZettleApi\API\Product\Product;
use LauLamanApps\iZettleApi\API\Product\Variant;
use LauLamanApps\iZettleApi\API\Product\VariantCollection;
use LauLamanApps\iZettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\iZettleApi\Client\AccessToken;
use LauLamanApps\iZettleApi\iZettleClient;
use Mockery;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class iZettleClientTest extends TestCase
{
    const ACCESS_TOKEN = 'access-token';
    const DEFAULT_ORGANIZATION_UUID = 'self';

    /**
     * @test
     */
    public function setOrganizationUuid()
    {
        $newOrganizationUuid = 123456;
        $iZettleClient = new iZettleClient(new GuzzleClient(), $this->getAccessToken());
        self::assertAttributeEquals(self::DEFAULT_ORGANIZATION_UUID, 'organizationUuid', $iZettleClient);

        $iZettleClient->setOrganizationUuid($newOrganizationUuid);

        self::assertAttributeEquals($newOrganizationUuid, 'organizationUuid', $iZettleClient);
    }

    /**
     * @test
     */
    public function getCategories()
    {
        $method = 'get';
        $url = sprintf(iZettleClient::PRODUCT_CATEGORY_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $categories = $iZettleClient->getCategories();
        self::assertTrue(is_array($categories));
    }

    /**
     * @test
     */
    public function createCategory()
    {
        $category = Category::new('name');
        $method = 'post';
        $url = sprintf(iZettleClient::PRODUCT_CATEGORY_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $category->getCreateData(),
        ];

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $iZettleClient->createCategory($category);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function getDiscounts()
    {
        $method = 'get';
        $url = sprintf(iZettleClient::PRODUCT_DISCOUNT_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $discounts = $iZettleClient->getDiscounts();
        self::assertTrue(is_array($discounts));
    }

    /**
     * @test
     */
    public function createDiscount()
    {
        $discount = $this->getDiscount();
        $method = 'post';
        $url = sprintf(iZettleClient::PRODUCT_DISCOUNT_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $discount->getCreateData(),
        ];

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $iZettleClient->createDiscount($discount);

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
            iZettleClient::PRODUCT_DISCOUNT_SINGLE,
            self::DEFAULT_ORGANIZATION_UUID,
            (string) $discount->getUuid()
        );
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
            ],
        ];

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $iZettleClient->deleteDiscount($discount);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function getLibrary()
    {
        $method = 'get';
        $url = sprintf(iZettleClient::PRODUCT_LIBRARY, self::DEFAULT_ORGANIZATION_UUID);
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

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $library = $iZettleClient->getLibrary();
        self::assertInstanceOf(Library::class, $library);
    }

    /**
     * @test
     */
    public function getProducts()
    {
        $method = 'get';
        $url = sprintf(iZettleClient::PRODUCT_PRODUCTS_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $return = [

        ];

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $products = $iZettleClient->getProducts();
        self::assertTrue(is_array($products));
    }

    /**
     * @test
     */
    public function createProduct()
    {
        $product = $this->getProduct();
        $method = 'post';
        $url = sprintf(iZettleClient::PRODUCT_PRODUCTS_ALL, self::DEFAULT_ORGANIZATION_UUID);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $product->getCreateData(),
        ];

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $iZettleClient->createProduct($product);

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
            iZettleClient::PRODUCT_PRODUCTS_SINGLE,
            self::DEFAULT_ORGANIZATION_UUID,
            (string) $product->getUuid()
        );
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN),
            ],
        ];

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options), $this->getAccessToken());
        $iZettleClient->deleteProduct($product);

        self::assertTrue(true); //-- fix till issue is solved: https://github.com/mockery/mockery/issues/376
    }

    /**
     * @test
     */
    public function getPurchaseHistory()
    {
        $method = 'get';
        $url = sprintf(iZettleClient::PURCHASE_HISTORY_URL, self::DEFAULT_ORGANIZATION_UUID);
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

        $iZettleClient = new iZettleClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $purchaseHistory = $iZettleClient->getPurchaseHistory();
        self::assertInstanceOf(PurchaseHistory::class, $purchaseHistory);
    }

    /**
     * @test
     * @expectedException \LauLamanApps\iZettleApi\Client\Exceptions\AccessTokenExpiredException
     */
    public function validateAccessToken()
    {
        $invalidAccessToken =  new AccessToken('', new DateTime('-1 day'), '');

        new iZettleClient(new GuzzleClient(), $invalidAccessToken);
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
