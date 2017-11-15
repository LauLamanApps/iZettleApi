<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Image;

use LauLamanApps\IzettleApi\Client\Image\ImageFileUpload;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class ImageFileUploadTest extends TestCase
{
    /**
     * @test
     */
    public function productImageUpload_withImage(): void
    {
        $file = dirname(__FILE__) . '/files/50x50-good.png';
        $imageFormat = 'PNG';
        $imageData = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode(file_get_contents($file)));
        $productImageUpload = new ImageFileUpload($file);

        self::assertInstanceOf(ImageFileUpload::class, $productImageUpload);
        self::assertAttributeEquals($imageFormat, 'imageFormat', $productImageUpload);
        self::assertAttributeEquals($imageData, 'imageData', $productImageUpload);
        self::assertSame(['imageFormat' => $imageFormat, 'imageData' => $imageData], json_decode($productImageUpload->getPostBodyData(), true));
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Image\Exceptions\MaximumImageFileSizeExcededException
     */
    public function fileSizeIsToBig_shouldThrowException(): void
    {
        new ImageFileUpload(dirname(__FILE__) . '/files/3840x2160-filesize-to-big.png');
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Image\Exceptions\FileIsNotAnImageException
     */
    public function fileIsNotAnImage_shouldThrowException(): void
    {
        new ImageFileUpload(dirname(__FILE__) . '/files/text.txt');
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Image\Exceptions\ImageTypeNotAllowedException
     */
    public function notAllowedFileType_shouldThrowException(): void
    {
        new ImageFileUpload(dirname(__FILE__) . '/files/50x50-file-type-not-allowed.swf');
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Image\Exceptions\ImageIsToSmallException
     */
    public function toSmallImageSize_shouldThrowException(): void
    {
        new ImageFileUpload(dirname(__FILE__) . '/files/1x1-to-small.png');
    }
}
