<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\Library;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\Client\AccessToken;
use LauLamanApps\IzettleApi\Client\Exceptions\AccessTokenExpiredException;
use LauLamanApps\IzettleApi\Client\Product\CategoryParser;
use LauLamanApps\IzettleApi\Client\Product\DiscountParser;
use LauLamanApps\IzettleApi\Client\Product\LibraryParser;
use LauLamanApps\IzettleApi\Client\Product\ProductParser;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseHistoryParser;
use Psr\Http\Message\ResponseInterface;

final class IzettleClient
{
    /**
     * Izettle' PRODUCTS domain
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
     * Izettle' Purchase domain
     * API documentation https://products.izettle.com/swagger#/
     */
    const PURCHASE_BASE_URL = 'https://purchase.izettle.com';
    const PURCHASE_HISTORY_URL = self::PURCHASE_BASE_URL . '/purchases/v2';

    private $guzzleClient;
    private $accessToken;
    private $organizationUuid = 'self';

    public function __construct(GuzzleClient $guzzleClient, AccessToken $accessToken)
    {
        $this->guzzleClient = $guzzleClient;
        $this->accessToken = $accessToken;
        $this->validateAccessToken();
    }

    public function setOrganizationUuid(int $organizationUuid): void
    {
        $this->organizationUuid = $organizationUuid;
    }

    public function getCategories(): array
    {
        $url = sprintf(self::PRODUCT_CATEGORY_ALL, $this->organizationUuid);

        return CategoryParser::createFromResponse($this->get($url));
    }

    public function createCategory(Category $category): void
    {
        $url = sprintf(self::PRODUCT_CATEGORY_ALL, $this->organizationUuid);
        $this->post($url, $category->getCreateData());
    }

    public function getDiscounts(): array
    {
        $url = sprintf(self::PRODUCT_DISCOUNT_ALL, $this->organizationUuid);

        return DiscountParser::createFromResponse($this->get($url));
    }

    public function createDiscount(Discount $discount): void
    {
        $url = sprintf(self::PRODUCT_DISCOUNT_ALL, $this->organizationUuid);

        $this->post($url, $discount->getCreateData());
    }

    public function deleteDiscount(Discount $discount): void
    {
        $url = sprintf(self::PRODUCT_DISCOUNT_SINGLE, $this->organizationUuid, (string) $discount->getUuid());

        $this->delete($url);
    }

    public function getLibrary(): Library
    {
        $url = sprintf(self::PRODUCT_LIBRARY, $this->organizationUuid);

        return LibraryParser::createFromResponse($this->get($url));
    }

    public function getProducts(): array
    {
        $url = sprintf(self::PRODUCT_PRODUCTS_ALL, $this->organizationUuid);

        return ProductParser::createFromResponse($this->get($url));
    }

    public function createProduct(Product $product): void
    {
        $url = sprintf(self::PRODUCT_PRODUCTS_ALL, $this->organizationUuid);

        $this->post($url, $product->getCreateData());
    }

    public function deleteProduct(Product $product): void
    {
        $url = sprintf(self::PRODUCT_PRODUCTS_SINGLE, $this->organizationUuid, (string) $product->getUuid());

        $this->delete($url);
    }

    public function getPurchaseHistory(): PurchaseHistory
    {
        return PurchaseHistoryParser::createFromResponse($this->get(self::PURCHASE_HISTORY_URL));
    }

    private function get(string $url, ?array $queryParameters = null): ResponseInterface
    {
        $options =  array_merge(['headers' => $this->getAuthorizationHeader()], ['query' => $queryParameters]);

        return $this->guzzleClient->get($url, $options);
    }

    private function post(string $url, string $data): void
    {
        $headers = array_merge(
            $this->getAuthorizationHeader(),
            [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $options =  array_merge(['headers' => $headers], ['body' => $data]);

        $this->guzzleClient->post($url, $options);
    }

    private function delete(string $url): void
    {
        $this->guzzleClient->delete($url, ['headers' => $this->getAuthorizationHeader()]);
    }

    private function validateAccessToken(): void
    {
        if ($this->accessToken->isExpired()) {
            throw new AccessTokenExpiredException(
                sprintf(
                    'Access Token was valid till \'%s\' its now \'%s\'',
                    $this->accessToken->getExpires()->format('Y-m-d H:i:s.u'),
                    (new DateTime())->format('Y-m-d H:i:s.u')
                )
            );
        }
    }

    private function getAuthorizationHeader(): array
    {
        return ['Authorization' => sprintf('Bearer %s', $this->accessToken->getToken())];
    }
}
