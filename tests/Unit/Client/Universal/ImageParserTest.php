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
        $json = file_get_contents(__DIR__ . '/json-files/buildFromJson.json');
        $data = json_decode($json, true);

        $builder = new ImageBuilder();
        $image = $builder->buildFromJson($json);

        $this->assertInstanceOf(Image::class, $image);
        $this->assertSame($data['imageLookupKey'], $image->getFilename());
        $this->assertContains($image->getSmallImageUrl(), $data['imageUrls']);
        $this->assertContains($image->getLargeImageUrl(), $data['imageUrls']);
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

        $this->assertInstanceOf(ImageCollection::class, $imageCollection);
        $this->assertSame(count($data), count($imageCollection->getAll()));

        foreach ($imageCollection->getAll() as $image) {
            $this->assertInstanceOf(Image::class, $image);
        }
    }
}
