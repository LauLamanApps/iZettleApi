<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client;

use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\API\Product\DiscountCollection;
use LauLamanApps\IzettleApi\API\Product\Library;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Product\ProductCollection;
use LauLamanApps\IzettleApi\API\Product\Variant;
use LauLamanApps\IzettleApi\API\Product\VariantCollection;
use LauLamanApps\IzettleApi\Client\Product\CategoryBuilderInterface;
use LauLamanApps\IzettleApi\Client\Product\DiscountBuilderInterface;
use LauLamanApps\IzettleApi\Client\Product\LibraryBuilderInterface;
use LauLamanApps\IzettleApi\Client\Product\ProductBuilderInterface;
use LauLamanApps\IzettleApi\Client\ProductClient;
use LauLamanApps\IzettleApi\IzettleClientInterface;
use LauLamanApps\IzettleApi\Tests\Unit\MockeryAssertionTrait;
use Mockery;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class ProductClientTest extends AbstractClientMock
{
    /**
     * @test
     */
    public function getCategories()
    {
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::GET_CATEGORIES, (string) $organizationUuid);
        $data = ['getCategoriesTest'];

        $izettleClientMock = $this->getIzettleGetMock($url, $data);

        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();

        $categoryBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );
        $productClient->getCategories();
    }

    /**
     * @test
     */
    public function createCategory()
    {
        $organizationUuid = Uuid::uuid1();
        $category = Category::new('name');
        $url = sprintf(ProductClient::POST_CATEGORY, (string) $organizationUuid);

        $izettleClientMock = $this->getIzettlePostMock($url, $category->getCreateData());
        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->createCategory($category);
    }

    /**
     * @test
     */
    public function getDiscounts()
    {
        $organizationUuid = Uuid::uuid1();
        $data = ['getDiscountsTest'];
        $url = sprintf(ProductClient::GET_DISCOUNTS, $organizationUuid);

        $izettleClientMock = $this->getIzettleGetMock($url, $data);
        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();
        $discountBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->getDiscounts();
    }

    /**
     * @test
     */
    public function createDiscount()
    {
        $discount = $this->getDiscount();
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::POST_DISCOUNT, (string) $organizationUuid);

        $izettleClientMock = $this->getIzettlePostMock($url, $discount->getCreateData());
        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->createDiscount($discount);
    }

    /**
     * @test
     */
    public function deleteDiscount()
    {
        $discount = $this->getDiscount();
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::DELETE_DISCOUNT, (string) $organizationUuid, (string) $discount->getUuid());

        $izettleClientMock = $this->getIzettleDeleteMock($url);
        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->deleteDiscount($discount);
    }

    /**
     * @test
     */
    public function getLibrary()
    {
        $organizationUuid = Uuid::uuid1();
        $data = ['getLibraryTest'];
        $url = sprintf(ProductClient::GET_LIBRARY, $organizationUuid);

        $izettleClientMock = $this->getIzettleGetMock($url, $data);
        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();
        $libraryBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once()
            ->andReturn(new Library(
                Uuid::uuid1(),
                Uuid::uuid1(),
                new ProductCollection(),
                new DiscountCollection(),
                new ProductCollection(),
                new DiscountCollection()
            ));

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->getLibrary();
    }

    /**
     * @test
     */
    public function getProducts()
    {
        $organizationUuid = Uuid::uuid1();
        $data = ['getProductsTest'];
        $url = sprintf(ProductClient::GET_PRODUCTS, $organizationUuid);

        $izettleClientMock = $this->getIzettleGetMock($url, $data);
        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();
        $productBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->getProducts();
    }

    /**
     * @test
     */
    public function createProduct()
    {
        $product = $this->getProduct();
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::POST_PRODUCT, (string) $organizationUuid);

        $izettleClientMock = $this->getIzettlePostMock($url, $product->getCreateData());
        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->createProduct($product);
    }

    /**
     * @test
     */
    public function deleteProduct()
    {
        $product = $this->getProduct();
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::DELETE_PRODUCT, (string) $organizationUuid, (string) $product->getUuid());

        $izettleClientMock = $this->getIzettleDeleteMock($url);
        list($categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock) = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->deleteProduct($product);
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

    protected function getDependencyMocks(): array
    {
        return [
            Mockery::mock(CategoryBuilderInterface::class),
            Mockery::mock(DiscountBuilderInterface::class),
            Mockery::mock(LibraryBuilderInterface::class),
            Mockery::mock(ProductBuilderInterface::class),
        ];
    }
}
