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
        $imageUrl = 'https://example.com/image.png';

        $productImageUpload = new ImageUrlUpload($imageUrl);

        self::assertInstanceOf(ImageUrlUpload::class, $productImageUpload);
        self::assertAttributeEquals($imageUrl, 'imageUrl', $productImageUpload);
        self::assertSame(['imageUrl' => $imageUrl], $productImageUpload->getUploadRequest());
    }
}
