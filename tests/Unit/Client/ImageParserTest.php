<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Client;

use LauLamanApps\iZettleApi\API\Image;
use LauLamanApps\iZettleApi\API\ImageCollection;
use LauLamanApps\iZettleApi\Client\ImageParser;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class ImageParserTest extends TestCase
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

        $imageCollection = ImageParser::parseArray($data);

        self::assertInstanceOf(ImageCollection::class, $imageCollection);
        self::assertSame(count($data), count($imageCollection->getAll()));

        foreach ($imageCollection->getAll() as $image) {
            self::assertInstanceOf(Image::class, $image);
        }
    }
}
