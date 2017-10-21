<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Client\Product;

use DateTime;
use LauLamanApps\iZettleApi\API\ImageCollection;
use LauLamanApps\iZettleApi\API\Product\CategoryCollection;
use LauLamanApps\iZettleApi\API\Product\Product;
use LauLamanApps\iZettleApi\API\Product\VariantCollection;
use LauLamanApps\iZettleApi\Client\Product\ProductParser;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class ProductParserTest extends TestCase
{
    /**
     * @test
     * @dataProvider getProductResponseData
     */
    public function createFromResponse(ResponseInterface $response, $data)
    {
        /** @var Product[] $products */
        $products = ProductParser::createFromResponse($response);

        foreach ($products as $index => $product) {
            self::assertInstanceOf(Product::class, $product);
            self::assertSame($data[$index]['uuid'], (string) $product->getUuid());
            self::assertInstanceOf(CategoryCollection::class, $product->getCategories());
            self::assertSame($data[$index]['name'], $product->getName());
            self::assertSame($data[$index]['description'], $product->getDescription());
            self::assertInstanceOf(ImageCollection::class, $product->getImageLookupKeys());
            self::assertInstanceOf(VariantCollection::class, $product->getVariants());
            self::assertSame($data[$index]['variants'], $product->getVariants()->getAll());
            self::assertSame($data[$index]['externalReference'], $product->getExternalReference());
            self::assertSame($data[$index]['etag'], $product->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $product->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $product->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $product->getCreatedAt());
            self::assertSame((float)$data[$index]['vatPercentage'], $product->getVatPercentage());
        }
    }

    public function getProductResponseData(): array
    {
        $data1 = [[
            'uuid' => (string) Uuid::uuid1(),
            'categories' => [],
            'name' => 'Some Product',
            'description' => '',
            'imageLookupKeys' => [
                'nice-image.jpeg',
            ],
            'variants' => [],
            'externalReference' => '',
            'etag' => 'B1C54B44DB967F4240B59AFA30B1AC5E',
            'updated' => '2017-12-06T13:21:59.722+0000',
            'updatedBy' => (string) Uuid::uuid1(),
            'created' => '2017-12-21T13:12:49.272+0000',
            'vatPercentage' => '21.0'
        ]];
        $mock1 = Mockery::mock(ResponseInterface::class);
        $mock1->shouldReceive('getBody')->andReturnSelf();
        $mock1->shouldReceive('getContents')->andReturn(json_encode(
            $data1
        ));

        return [
            'single product' => [ $mock1, $data1 ]
        ];
    }
}
