<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi;

use GuzzleHttp\Client as GuzzleClient;
use LauLamanApps\iZettleApi\API\Product\Category;
use LauLamanApps\iZettleApi\API\Product\Discount;
use LauLamanApps\iZettleApi\API\Product\Library;
use LauLamanApps\iZettleApi\API\Product\Product;
use LauLamanApps\iZettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\iZettleApi\Client\AccessToken;
use LauLamanApps\iZettleApi\Client\AccessTokenExpired;
use LauLamanApps\iZettleApi\Client\Product\CategoryParser;
use LauLamanApps\iZettleApi\Client\Product\DiscountParser;
use LauLamanApps\iZettleApi\Client\Product\LibraryParser;
use LauLamanApps\iZettleApi\Client\Product\ProductParser;
use LauLamanApps\iZettleApi\Client\Purchase\PurchaseHistoryParser;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\UuidInterface;

final class Client
{
    /**
     * iZettle' PRODUCTS domain
     * API documentation https://products.izettle.com/swagger#/
     */
    const PRODUCT_BASE_URL = 'https://products.izettle.com';
    const PRODUCT_CATEGORY_ALL = self::PRODUCT_BASE_URL . '/organizations/%s/categories';
    const PRODUCT_CATEGORY_SINGLE = self::PRODUCT_CATEGORY_ALL . '/%s';
    const PRODUCT_DISCOUNT_ALL = self::PRODUCT_BASE_URL . '/organizations/%s/discounts';
    const PRODUCT_DISCOUNT_SINGLE = self::PRODUCT_DISCOUNT_ALL . '/%s';
    const PRODUCT_LIBRARY = self::PRODUCT_BASE_URL . '/organizations/%s/library';
    const PRODUCT_PRODUCTS_ALL = self::PRODUCT_BASE_URL . '/organizations/%s/products';
    const PRODUCT_PRODUCTS_SINGLE = self::PRODUCT_PRODUCTS_ALL . '/%s';

    /**
     * iZettle' Purchase domain
     * API documentation https://products.izettle.com/swagger#/
     */
    const PURCHASE_BASE_URL = 'https://purchase.izettle.com';
    const PURCHASE_HISTORY_URL = self::PURCHASE_BASE_URL . '/purchases/v2';

    private $accessToken;

    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->validateAccessToken();
    }

    public function getCategories(?string $organizationUuid = null): array
    {
        $url = sprintf(self::PRODUCT_DISCOUNT_ALL, $this->getOrgUuid($organizationUuid));

        return CategoryParser::createFromResponse($this->get($url));
    }

    public function createCategory(Category $category, ?UuidInterface $organizationUuid = null): void
    {
        $url = sprintf(self::PRODUCT_CATEGORY_ALL, $this->getOrgUuid($organizationUuid));

        $this->post($url, $category->getCreateData());
    }

    public function getDiscounts(?string $organizationUuid = null): array
    {
        $url = sprintf(self::PRODUCT_DISCOUNT_ALL, $this->getOrgUuid($organizationUuid));

        return DiscountParser::createFromResponse($this->get($url));
    }

    public function createDiscount(Discount $discount, ?UuidInterface $organizationUuid = null): void
    {
        $url = sprintf(self::PRODUCT_DISCOUNT_ALL, $this->getOrgUuid($organizationUuid));

        $this->post($url, $discount->getCreateData());
    }

    public function deleteDiscount(Discount $discount, ?UuidInterface $organizationUuid = null):void
    {
        $url = sprintf(self::PRODUCT_DISCOUNT_SINGLE, $this->getOrgUuid($organizationUuid), (string) $discount->getUuid());

        $this->delete($url);
    }

    public function getLibrary(?string $organizationUuid = null): Library
    {
        $url = sprintf(self::PRODUCT_LIBRARY, $this->getOrgUuid($organizationUuid));

        return LibraryParser::createFromResponse($this->get($url));
    }

    public function getProducts(?string $organizationUuid = null): array
    {
        $url = sprintf(self::PRODUCT_PRODUCTS_ALL, $this->getOrgUuid($organizationUuid));

        return ProductParser::createFromResponse($this->get($url));
    }

    public function createProduct(Product $product, ?string $organizationUuid = null): void
    {
        $url = sprintf(self::PRODUCT_PRODUCTS_ALL, $this->getOrgUuid($organizationUuid));

        $this->post($url, $product->getCreateData());
    }

    public function deleteProduct(Product $product, ?string $organizationUuid = null): void
    {
        $url = sprintf(self::PRODUCT_PRODUCTS_SINGLE, $this->getOrgUuid($organizationUuid), (string) $product->getUuid());

        $this->delete($url);
    }


    public function getPurchaseHistory(): PurchaseHistory
    {
        return PurchaseHistoryParser::createFromResponse($this->get(self::PURCHASE_HISTORY_URL));
    }

    private function get(string $url, ?array $queryParameters = null): ResponseInterface
    {
        $options['query'] = $queryParameters;

        return $this->getClient()->get($url, $options);
    }

    private function post(string $url, string $data): void
    {
        $options['headers'] = [
            'content-type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $options['body'] = $data;

        $this->getClient()->post($url, $options);
    }

    private function delete(string $url): void
    {
        $this->getClient()->delete($url);
    }

    private function validateAccessToken()
    {
        if ($this->accessToken->isExpired()) {
            throw new AccessTokenExpired(
                sprintf(
                    'Access Token was valid till \'%s\'',
                    $this->accessToken->getExpires()->format('Y-m-d H:i:s')
                )
            );
        }
    }

    private function getOrgUuid($organizationUuid): string
    {
        return $organizationUuid ?? 'self';
    }

    private function getClient(): GuzzleClient
    {
        return new GuzzleClient(['headers' => ['Authorization' => sprintf('Bearer %s', (string) $this->accessToken)]]);
    }
}
