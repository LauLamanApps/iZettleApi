<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Image;

use LauLamanApps\IzettleApi\Client\Image\ImageUrlUpload;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class ImageUrlUploadTest extends TestCase
{
    /**
     * @test
     */
    public function productImageUpload_withURL(): void
    {
        $allowedImageType = [
            'gif'  => 'GIF',
            'jpeg' => 'JPEG',
            'jpg'  => 'JPEG',
            'png'  => 'PNG',
            'bmp'  => 'BMP',
        ];

        $imageUrl       = 'https://example.com/image.png';
        $imageFormat    = $allowedImageType[
            strtolower(array_values(array_slice(explode('.', $imageUrl), -1))[0])
        ];

        $productImageUpload = new ImageUrlUpload($imageUrl);

        self::assertInstanceOf(ImageUrlUpload::class, $productImageUpload);
        self::assertAttributeEquals($imageFormat, 'imageFormat', $productImageUpload);
        self::assertAttributeEquals($imageUrl, 'imageUrl', $productImageUpload);
        // self::assertSame(['imageUrl' => $imageUrl], json_decode($productImageUpload->getPostBodyData(), true));
        self::assertSame(['imageFormat' => $imageFormat, 'imageData' => $imageData], json_decode($productImageUpload->getPostBodyData(), true));
    }
}
