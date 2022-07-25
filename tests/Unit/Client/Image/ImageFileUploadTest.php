<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Image;

use LauLamanApps\IzettleApi\Client\Image\Exceptions\FileIsNotAnImageException;
use LauLamanApps\IzettleApi\Client\Image\Exceptions\ImageIsToSmallException;
use LauLamanApps\IzettleApi\Client\Image\Exceptions\ImageTypeNotAllowedException;
use LauLamanApps\IzettleApi\Client\Image\Exceptions\MaximumImageFileSizeExcededException;
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
        $file = __DIR__ . '/files/50x50-good.png';
        $imageFormat = 'PNG';
        $imageData = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode(file_get_contents($file)));
        $productImageUpload = new ImageFileUpload($file);

        $this->assertInstanceOf(ImageFileUpload::class, $productImageUpload);
        $this->assertSame(['imageFormat' => $imageFormat, 'imageData' => $imageData], json_decode($productImageUpload->getPostBodyData(), true));
    }

    /**
     * @test
     */
    public function fileSizeIsToBig_shouldThrowException(): void
    {
        $this->expectException(MaximumImageFileSizeExcededException::class);

        new ImageFileUpload(__DIR__ . '/files/3840x2160-filesize-to-big.png');
    }

    /**
     * @test
     */
    public function fileIsNotAnImage_shouldThrowException(): void
    {
        $this->expectException(FileIsNotAnImageException::class);

        new ImageFileUpload(__DIR__ . '/files/text.txt');
    }

    /**
     * @test
     */
    public function notAllowedFileType_shouldThrowException(): void
    {
        $this->expectException(ImageTypeNotAllowedException::class);

        new ImageFileUpload(__DIR__ . '/files/50x50-file-type-not-allowed.swf');
    }

    /**
     * @test
     */
    public function toSmallImageSize_shouldThrowException(): void
    {
        $this->expectException(ImageIsToSmallException::class);

        new ImageFileUpload(__DIR__ . '/files/1x1-to-small.png');
    }
}
