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

        $this->assertInstanceOf(ImageUrlUpload::class, $productImageUpload);
        $this->assertSame(['imageUrl' => $imageUrl], json_decode($productImageUpload->getPostBodyData(), true));
    }
}
