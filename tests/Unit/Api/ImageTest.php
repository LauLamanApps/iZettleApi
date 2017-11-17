<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api;

use LauLamanApps\IzettleApi\API\Image;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class ImageTest extends TestCase
{
    /**
     * @test
     */
    public function image(): void
    {
        $filename = 'image.jpg';

        $image = new Image($filename);

        self::assertSame(Image::BASE_URL . 'o/' . $filename, $image->getLargeImageUrl());
        self::assertSame(Image::BASE_URL . 'L/' . $filename, $image->getSmallImageUrl());
    }
}
