<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Product\Discount;
use LauLamanApps\IzettleApi\Client\Product\DiscountParser;
use Mockery;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class DiscountParserTest extends TestCase
{
    /**
     * @test
     * @dataProvider getProductResponseData
     */
    public function createFromResponse(ResponseInterface $response, $data)
    {
        /** @var Discount[] $discounts */
        $discounts = DiscountParser::createFromResponse($response);

        foreach ($discounts as $index => $discount) {
            self::assertInstanceOf(Discount::class, $discount);
            self::assertSame($data[$index]['uuid'], (string) $discount->getUuid());
            self::assertSame($data[$index]['name'], $discount->getName());
            self::assertSame($data[$index]['description'], $discount->getDescription());
            self::assertInstanceOf(ImageCollection::class, $discount->getImageCollection());
            self::assertSame($data[$index]['externalReference'], $discount->getExternalReference());
            self::assertSame($data[$index]['etag'], $discount->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $discount->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $discount->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $discount->getCreatedAt());

            if ($data[$index]['amount']) {
                self::assertInstanceOf(Money::class, $discount->getAmount());
                self::assertSame((string) $data[$index]['amount']['amount'], $discount->getAmount()->getAmount());
                self::assertSame($data[$index]['amount']['currencyId'], $discount->getAmount()->getCurrency()->getCode());
            } else {
                self::assertSame((float) $data[$index]['percentage'], $discount->getPercentage());
            }
        }
    }

    public function getProductResponseData(): array
    {
        $data1 = [[
            'uuid' => (string) Uuid::uuid1(),
            'name' => 'Some Product',
            'description' => 'description',
            'imageLookupKeys' => [
                'nice-image.jpeg',
            ],
            'amount' => ['amount' => 1000, 'currencyId' => 'EUR'],
            'percentage' => null,
            'externalReference' => 'externalReference',
            'etag' => 'B1C54B44DB967F4240B59AFA30B1AC5E',
            'updated' => '2017-12-06T13:21:59.722+0000',
            'updatedBy' => (string) Uuid::uuid1(),
            'created' => '2017-12-21T13:12:49.272+0000',
        ]];

        $mock1 = Mockery::mock(ResponseInterface::class);
        $mock1->shouldReceive('getBody')->andReturnSelf();
        $mock1->shouldReceive('getContents')->andReturn(json_encode(
            $data1
        ));

        $data2 = [
            [
                'uuid' => (string) Uuid::uuid1(),
                'name' => 'discount1',
                'description' => 'descriptionq',
                'imageLookupKeys' => [
                    'image1.jpeg',
                ],
                'amount' => ['amount' => 100, 'currencyId' => 'EUR'],
                'percentage' => null,
                'externalReference' => 'externalReference',
                'etag' => '93D4F5F748933923890C91056A6F0230',
                'updated' => '2017-12-06T13:21:59.722+0000',
                'updatedBy' => (string) Uuid::uuid1(),
                'created' => '2017-12-21T13:12:49.272+0000',
            ],
            [
                'uuid' => (string) Uuid::uuid1(),
                'name' => 'discount2',
                'description' => 'description2',
                'imageLookupKeys' => [
                    'image2.jpeg',
                ],
                'amount' => null,
                'percentage' => '10',
                'externalReference' => 'externalReference2',
                'etag' => '35D7775289BC0A05E580BE3B466C927B',
                'updated' => '2017-12-06T13:21:59.722+0000',
                'updatedBy' => (string) Uuid::uuid1(),
                'created' => '2017-12-21T13:12:49.272+0000',
            ]
        ];

        $mock2 = Mockery::mock(ResponseInterface::class);
        $mock2->shouldReceive('getBody')->andReturnSelf();
        $mock2->shouldReceive('getContents')->andReturn(json_encode(
            $data2
        ));

        return [
            'single discount' => [ $mock1, $data1 ],
            'multiple discount' => [ $mock2, $data2 ],
        ];
    }
}
