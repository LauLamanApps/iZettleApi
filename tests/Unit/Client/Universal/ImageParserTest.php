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
    public function parseArray(): void
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
