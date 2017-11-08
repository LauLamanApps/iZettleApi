<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Universal;

use LauLamanApps\IzettleApi\API\Image;
use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\Client\Universal\ImageBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class ImageBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function buildFromJson(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/json-files/buildFromJson.json');
        $data = json_decode($json, true);

        $builder = new ImageBuilder();
        $image = $builder->buildFromJson($json);

        self::assertInstanceOf(Image::class, $image);
        self::assertSame($data['imageLookupKey'], $image->getFilename());
        self::assertContains($image->getSmallImageUrl(), $data['imageUrls']);
        self::assertContains($image->getLargeImageUrl(), $data['imageUrls']);
    }

    /**
     * @test
     */
    public function buildFromArray(): void
    {
        $data = [
            "a.jpeg",
            "b.png",
            "c.gif",
        ];

        $builder = new ImageBuilder();
        $imageCollection = $builder->buildFromArray($data);

        self::assertInstanceOf(ImageCollection::class, $imageCollection);
        self::assertSame(count($data), count($imageCollection->getAll()));

        foreach ($imageCollection->getAll() as $image) {
            self::assertInstanceOf(Image::class, $image);
        }
    }
}
