<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api;

use LauLamanApps\IzettleApi\API\Image;
use LauLamanApps\IzettleApi\API\ImageCollection;
use PHPUnit\Framework\TestCase;
/**
 * @small
 */
final class ImageCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function imageCollection(): void
    {
        $image1 = $this->getImageWithUuid('a.jpg');
        $image2 = $this->getImageWithUuid('b.png');
        $image3 = $this->getImageWithUuid('c.gif');

        $imageCollection = new ImageCollection([$image1, $image2, $image3]);
        $imageCollection->add($image3);// add image 3 again it should only end up once in the collection

        //-- Check if collection contains all 3 images
        $collection = $imageCollection->getAll();
        self::assertEquals(3, count($collection));
        self::assertEquals($image1, $collection[$image1->getFilename()]);
        self::assertEquals($image2, $collection[$image2->getFilename()]);
        self::assertEquals($image3, $collection[$image3->getFilename()]);

        $imageCollection->remove($image2);

        //-- Check if collection does not contains image 2 but does contain the others
        $collection = $imageCollection->getAll();
        self::assertEquals(2, count($collection));
        self::assertEquals($image1, $collection[$image1->getFilename()]);
        self::assertEquals($image3, $collection[$image3->getFilename()]);
        self::assertFalse(array_key_exists($image2->getFilename(), $collection));
    }

    /**
     * @test
     */
    public function get(): void
    {
        $filename = 'test.jpg';
        $imageCollection = new ImageCollection([$this->getImageWithUuid($filename)]);

        $image = $imageCollection->get($filename);
        self::assertSame($filename, $image->getFilename());
    }

    private function getImageWithUuid(string $filename): Image
    {
        return new Image($filename);
    }
}
