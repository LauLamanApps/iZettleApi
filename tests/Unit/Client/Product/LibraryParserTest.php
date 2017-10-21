<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Client\Product;

use LauLamanApps\iZettleApi\API\Product\DiscountCollection;
use LauLamanApps\iZettleApi\API\Product\ProductCollection;
use LauLamanApps\iZettleApi\Client\Product\LibraryParser;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class LibraryParserTest extends TestCase
{
    /**
     * @test
     */
    public function createFromResponse()
    {
        $data = $this->getData();
        $mockedResponse = Mockery::mock(ResponseInterface::class);
        $mockedResponse->shouldReceive('getBody')->andReturnSelf();
        $mockedResponse->shouldReceive('getContents')->andReturn(json_encode($data));

        $library = LibraryParser::createFromResponse($mockedResponse);

        self::assertSame(count($data['products']), count($library->getProducts()->getAll()));
        self::assertSame(count($data['discounts']), count($library->getDiscounts()->getAll()));

        self::assertInstanceOf(Uuid::class, $library->getFromEventLogUuid());
        self::assertInstanceOf(Uuid::class, $library->getUntilEventLogUuid());
        self::assertInstanceOf(ProductCollection::class, $library->getProducts());
        self::assertInstanceOf(DiscountCollection::class, $library->getDiscounts());
        self::assertInstanceOf(ProductCollection::class, $library->getDeletedProducts());
        self::assertInstanceOf(DiscountCollection::class, $library->getDeletedDiscounts());
    }

    public function getData(): array
    {
        return [
            'untilEventLogUuid' => (string) Uuid::uuid1(),
            'fromEventLogUuid' => (string) Uuid::uuid1(),
            'products' => [
                1 => [
                    'uuid' => (string) Uuid::uuid1(),
                    'categories' => [],
                    'name' => 'product1',
                    'description' => null,
                    'imageLookupKeys' => [],
                    'variants' => [
                        0 => [
                            'uuid' => (string) Uuid::uuid1(),
                            'name' => 'variant1',
                            'description' => null,
                            'sku' => null,
                            'barcode' => null,
                            'defaultQuantity' => '1',
                            'unitName' => null,
                            'price' => [
                                'amount' => 6500,
                                'currencyId' => 'EUR',
                            ],
                            'costPrice' => null,
                            'vatPercentage' => '6.0',
                        ],
                    ],
                    'externalReference' => null,
                    'etag' => 'CB7F0ABBF719F6A834727BF5FC59323C',
                    'updated' => '2017-04-21T13:16:36.166+0000',
                    'updatedBy' => (string) Uuid::uuid1(),
                    'created' => '2017-04-21T13:16:36.166+0000',
                    'unitName' => null,
                    'vatPercentage' => '6.0',
                ],
                4 => [
                    'uuid' => (string) Uuid::uuid1(),
                    'categories' => [],
                    'name' => 'product2',
                    'description' => null,
                    'imageLookupKeys' => [],
                    'variants' => [
                        0 => [
                            'uuid' => (string) Uuid::uuid1(),
                            'name' => 'variant2',
                            'description' => null,
                            'sku' => null,
                            'barcode' => null,
                            'defaultQuantity' => '1',
                            'unitName' => null,
                            'price' => [
                                'amount' => 12500,
                                'currencyId' => 'EUR',
                            ],
                            'costPrice' => null,
                            'vatPercentage' => '6.0',
                        ],
                        1 => [
                            'uuid' => (string) Uuid::uuid1(),
                            'name' => 'variant3',
                            'description' => null,
                            'sku' => null,
                            'barcode' => null,
                            'defaultQuantity' => '1',
                            'unitName' => null,
                            'price' => [
                                'amount' => 8000,
                                'currencyId' => 'EUR',
                            ],
                            'costPrice' => null,
                            'vatPercentage' => '6.0',
                        ],
                    ],
                    'externalReference' => null,
                    'etag' => 'FE0F62AD758BDAF477BDD86ECDA56E07',
                    'updated' => '2017-04-21T13:16:46.078+0000',
                    'updatedBy' => (string) Uuid::uuid1(),
                    'created' => '2017-04-21T13:16:00.273+0000',
                    'unitName' => null,
                    'vatPercentage' => '6.0',
                ],
            ],
            'discounts' => [
                0 => [
                    'uuid' => (string) Uuid::uuid1(),
                    'name' => 'discount1',
                    'description' => 'description1',
                    'imageLookupKeys' => [
                        0 => 'tomQNcudigYT0a_7MbxCIvMqoPM.jpeg'
                    ],
                    'amount' => null,
                    'percentage' => '80.5',
                    'externalReference' => 'none',
                    'etag' => '291231A318099EF63621193E8C4197F3',
                    'updated' => '2017-10-19T19:24:32.832+0000',
                    'updatedBy' => (string) Uuid::uuid1(),
                    'created' => '2017-10-19T19:24:32.832+0000',
                ],
                1 => [
                    'uuid' => (string) Uuid::uuid1(),
                    'name' => 'discount2',
                    'description' => 'description2',
                    'imageLookupKeys' => [],
                    'amount' => null,
                    'percentage' => '80.5',
                    'externalReference' => 'none',
                    'etag' => 'C4247311FFB2A873379358F95FAD10FD',
                    'updated' => '2017-10-19T19:16:40.773+0000',
                    'updatedBy' => (string) Uuid::uuid1(),
                    'created' => '2017-10-19T19:16:40.773+0000',
                ],
            ],
            'deletedProducts' => [],
            'deletedDiscounts' => [],
        ];
    }
}
