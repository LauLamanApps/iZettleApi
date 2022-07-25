<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client;

use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\Library;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\Client\Product\CategoryBuilder;
use LauLamanApps\IzettleApi\Client\Product\CategoryBuilderInterface;
use LauLamanApps\IzettleApi\Client\Product\DiscountBuilder;
use LauLamanApps\IzettleApi\Client\Product\DiscountBuilderInterface;
use LauLamanApps\IzettleApi\Client\Product\LibraryBuilder;
use LauLamanApps\IzettleApi\Client\Product\LibraryBuilderInterface;
use LauLamanApps\IzettleApi\Client\Product\ProductBuilder;
use LauLamanApps\IzettleApi\Client\Product\ProductBuilderInterface;
use LauLamanApps\IzettleApi\Exception\UnprocessableEntityException;
use LauLamanApps\IzettleApi\IzettleClientInterface;
use Ramsey\Uuid\UuidInterface;

final class ProductClient
{
    public const BASE_URL = 'https://products.izettle.com/organizations/%s';

    public const POST_CATEGORY = self::BASE_URL . '/categories';
    public const GET_CATEGORY = self::BASE_URL . '/categories/%s';
    public const GET_CATEGORIES = self::BASE_URL . '/categories';

    public const POST_DISCOUNT = self::BASE_URL . '/discounts';
    public const GET_DISCOUNT = self::BASE_URL . '/discounts/%s';
    public const PUT_DISCOUNT = self::BASE_URL . '/discounts/%s';
    public const DELETE_DISCOUNT = self::BASE_URL . '/discounts/%s';
    public const GET_DISCOUNTS = self::BASE_URL . '/discounts';

    public const GET_EXPORT = self::BASE_URL . '/products/%s';
    public const GET_EXPORT_TEMPLATE = self::BASE_URL . '/products/%s/template';

    public const GET_LIBRARY = self::BASE_URL . '/library';

    public const POST_PRODUCT = self::BASE_URL . '/products';
    public const GET_PRODUCT = self::BASE_URL . '/products/%s';
    public const PUT_PRODUCT = self::BASE_URL . '/products/v2/%s';
    public const DELETE_PRODUCT = self::BASE_URL . '/products/%s';
    public const POST_PRODUCT_VARIANT = self::BASE_URL . '/products/%s/variants';
    public const PUT_PRODUCT_VARIANT = self::BASE_URL . '/products/%s/variants/%s';
    public const DELETE_PRODUCT_VARIANT = self::BASE_URL . '/products/%s/variants/%s';
    public const GET_PRODUCTS = self::BASE_URL . '/products';
    public const DELETE_PRODUCTS = self::BASE_URL . '/products';

    private $client;
    private $organizationUuid;
    private $categoryBuilder;
    private $discountBuilder;
    private $libraryBuilder;
    private $productBuilder;

    public function __construct(
        IzettleClientInterface $client,
        ?UuidInterface $organizationUuid,
        CategoryBuilderInterface $categoryBuilder,
        DiscountBuilderInterface $discountBuilder,
        LibraryBuilderInterface $libraryBuilder,
        ProductBuilderInterface $productBuilder
    ) {
        $this->client = $client;
        $this->organizationUuid = $organizationUuid ? (string) $organizationUuid : 'self';
        $this->categoryBuilder = $categoryBuilder;
        $this->discountBuilder = $discountBuilder;
        $this->libraryBuilder = $libraryBuilder;
        $this->productBuilder = $productBuilder;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        $url = sprintf(self::GET_CATEGORIES, $this->organizationUuid);
        $json = $this->client->getJson($this->client->get($url));

        return $this->categoryBuilder->buildFromJson($json);
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function createCategory(Category $category): void
    {
        $url = sprintf(self::POST_CATEGORY, $this->organizationUuid);
        $this->client->post($url, $category);
    }

    /**
     * @return Discount[]
     */
    public function getDiscounts(): array
    {
        $url = sprintf(self::GET_DISCOUNTS, $this->organizationUuid);
        $json = $this->client->getJson($this->client->get($url));

        return $this->discountBuilder->buildFromJson($json);
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function createDiscount(Discount $discount): void
    {
        $url = sprintf(self::POST_DISCOUNT, $this->organizationUuid);

        $this->client->post($url, $discount);
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function deleteDiscount(Discount $discount): void
    {
        $url = sprintf(self::DELETE_DISCOUNT, $this->organizationUuid, (string) $discount->getUuid());

        $this->client->delete($url);
    }

    public function getLibrary(): Library
    {
        $url = sprintf(self::GET_LIBRARY, $this->organizationUuid);
        $json = $this->client->getJson($this->client->get($url));

        return $this->libraryBuilder->buildFromJson($json);
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        $url = sprintf(self::GET_PRODUCTS, $this->organizationUuid);
        $json = $this->client->getJson($this->client->get($url));

        return $this->productBuilder->buildFromJson($json);
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function createProduct(Product $product): void
    {
        $url = sprintf(self::POST_PRODUCT, $this->organizationUuid);

        $this->client->post($url, $product);
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function deleteProduct(Product $product): void
    {
        $url = sprintf(self::DELETE_PRODUCT, $this->organizationUuid, (string) $product->getUuid());

        $this->client->delete($url);
    }
}
