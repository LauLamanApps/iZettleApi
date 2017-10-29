<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\Product\Category;
use LauLamanApps\IzettleApi\API\Product\CategoryCollection;
use LauLamanApps\IzettleApi\Client\Product\CategoryBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class CategoryBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider getProductJson
     */
    public function buildFromJson(string $json): void
    {
        $builder = new CategoryBuilder();
        $categories = $builder->buildFromJson($json);
        $data =  json_decode($json, true);

        foreach ($categories as $index => $category) {
            self::assertInstanceOf(Category::class, $category);
            self::assertSame($data[$index]['uuid'], (string) $category->getUuid());
            self::assertSame($data[$index]['name'], $category->getName());
            self::assertSame($data[$index]['etag'], $category->getEtag());
            self::assertEquals(new DateTime($data[$index]['updated']), $category->getUpdatedAt());
            self::assertSame($data[$index]['updatedBy'], (string) $category->getUpdatedBy());
            self::assertEquals(new DateTime($data[$index]['created']), $category->getCreatedAt());
        }
    }

    /**
     * @test
     * @dataProvider getCategoryArrayData
     */
    public function buildFromArray(array $data): void
    {
        $builder = new CategoryBuilder();
        $categoryCollection = $builder->buildFromArray($data);

        self::assertInstanceOf(CategoryCollection::class, $categoryCollection);

        $i = 0;// we cannot use the array key here
        foreach ($categoryCollection->getAll() as $category) {
            self::assertSame($data[$i]['uuid'], (string) $category->getUuid());
            self::assertSame($data[$i]['name'], $category->getName());
            self::assertSame($data[$i]['etag'], $category->getEtag());
            self::assertEquals(new DateTime($data[$i]['updated']), $category->getUpdatedAt());
            self::assertSame($data[$i]['updatedBy'], (string) $category->getUpdatedBy());
            self::assertEquals(new DateTime($data[$i]['created']), $category->getCreatedAt());

            $i++;
        }
    }

    public function getProductJson(): array
    {
        $data = [[
            'uuid' => (string) Uuid::uuid1(),
            'name' => 'category1',
            'etag' => 'B1C54B44DB967F4240B59AFA30B1AC5E',
            'updated' => '2017-12-06T13:21:59.722+0000',
            'updatedBy' => (string) Uuid::uuid1(),
            'created' => '2017-12-21T13:12:49.272+0000',
        ]];

        return [
            'Single Category' => [ json_encode($data) ],
        ];
    }

    public function getCategoryArrayData(): array
    {
        return [
            'Single Category' => [
                [
                    [
                        'uuid' => (string) Uuid::uuid1(),
                        'name' => 'category2',
                        'etag' => 'B1C54B44DB967F4240B59AFA30B1AC5E',
                        'updated' => '2017-12-16T13:21:59.722+0000',
                        'updatedBy' => (string) Uuid::uuid1(),
                        'created' => '2019-12-28T14:12:49.272+0000',
                    ],
                ],
            ],
            'Multiple Category' =>[
                [
                    [
                        'uuid' => (string) Uuid::uuid1(),
                        'name' => 'category3',
                        'etag' => 'B1C54B44DB967F4240B59AFA30B1AC5E',
                        'updated' => '2017-10-18T13:21:59.722+0000',
                        'updatedBy' => (string) Uuid::uuid1(),
                        'created' => '2018-06-21T13:12:49.272+0000',
                    ],
                ],
                [
                    [
                        'uuid' => (string) Uuid::uuid1(),
                        'name' => 'category4',
                        'etag' => 'B1C54B44DB967F4240B59AFA30B1AC5E',
                        'updated' => '2017-05-02T13:21:59.722+0000',
                        'updatedBy' => (string) Uuid::uuid1(),
                        'created' => '2014-08-05T13:12:49.272+0000',
                    ],
                ],
            ],
        ];
    }
}
