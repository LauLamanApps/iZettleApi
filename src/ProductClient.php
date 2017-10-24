<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi;

use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\Library;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\Client\Product\CategoryParser;
use LauLamanApps\IzettleApi\Client\Product\DiscountParser;
use LauLamanApps\IzettleApi\Client\Product\LibraryParser;
use LauLamanApps\IzettleApi\Client\Product\ProductParser;

final class ProductClient extends AbstractClient
{
    const BASE_URL = 'https://products.izettle.com/organizations/%s';

    const POST_CATEGORY = self::BASE_URL . '/categories';
    const GET_CATEGORY = self::BASE_URL . '/categories/%s';
    const GET_CATEGORIES = self::BASE_URL . '/categories';

    const POST_DISCOUNT = self::BASE_URL . '/discounts';
    const GET_DISCOUNT = self::BASE_URL . '/discounts/%s';
    const PUT_DISCOUNT = self::BASE_URL . '/discounts/%s';
    const DELETE_DISCOUNT = self::BASE_URL . '/discounts/%s';
    const GET_DISCOUNTS = self::BASE_URL . '/discounts';

    const GET_EXPORT = self::BASE_URL . '/products/%s';
    const GET_EXPORT_TEMPLATE = self::BASE_URL . '/products/%s/template';

    const GET_LIBRARY = self::BASE_URL . '/library';

    const POST_PRODUCT = self::BASE_URL . '/products';
    const GET_PRODUCT = self::BASE_URL . '/products/%s';
    const PUT_PRODUCT = self::BASE_URL . '/products/v2/%s';
    const DELETE_PRODUCT = self::BASE_URL . '/products/%s';
    const POST_PRODUCT_VARIANT = self::BASE_URL . '/products/%s/variants';
    const PUT_PRODUCT_VARIANT = self::BASE_URL . '/products/%s/variants/%s';
    const DELETE_PRODUCT_VARIANT = self::BASE_URL . '/products/%s/variants/%s';
    const GET_PRODUCTS = self::BASE_URL . '/products';
    const DELETE_PRODUCTS = self::BASE_URL . '/products';

    public function getCategories(): array
    {
        $url = sprintf(self::GET_CATEGORIES, $this->getOrganizationUuid());

        return CategoryParser::createFromResponse($this->get($url));
    }

    public function createCategory(Category $category): void
    {
        $url = sprintf(self::POST_CATEGORY, $this->getOrganizationUuid());
        $this->post($url, $category->getCreateData());
    }

    public function getDiscounts(): array
    {
        $url = sprintf(self::GET_DISCOUNTS, $this->getOrganizationUuid());

        return DiscountParser::createFromResponse($this->get($url));
    }

    public function createDiscount(Discount $discount): void
    {
        $url = sprintf(self::POST_DISCOUNT, $this->getOrganizationUuid());

        $this->post($url, $discount->getCreateData());
    }

    public function deleteDiscount(Discount $discount): void
    {
        $url = sprintf(self::DELETE_DISCOUNT, $this->getOrganizationUuid(), (string) $discount->getUuid());

        $this->delete($url);
    }

    public function getLibrary(): Library
    {
        $url = sprintf(self::GET_LIBRARY, $this->getOrganizationUuid());

        return LibraryParser::createFromResponse($this->get($url));
    }

    public function getProducts(): array
    {
        $url = sprintf(self::GET_PRODUCTS, $this->getOrganizationUuid());

        return ProductParser::createFromResponse($this->get($url));
    }

    public function createProduct(Product $product): void
    {
        $url = sprintf(self::POST_PRODUCT, $this->getOrganizationUuid());

        $this->post($url, $product->getCreateData());
    }

    public function deleteProduct(Product $product): void
    {
        $url = sprintf(self::DELETE_PRODUCT, $this->getOrganizationUuid(), (string) $product->getUuid());

        $this->delete($url);
    }
}
