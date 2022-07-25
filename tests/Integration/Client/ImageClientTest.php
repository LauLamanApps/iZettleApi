<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Integration\Client;

use LauLamanApps\IzettleApi\API\Image;
use LauLamanApps\IzettleApi\Client\Image\ImageUrlUpload;
use LauLamanApps\IzettleApi\IzettleClientFactory;

/**
 * @medium
 */
final class ImageClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getPurchaseHistory(): void
    {
        $json = file_get_contents(__DIR__ . '/files/ImageClient/postImage.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $imageClient = IzettleClientFactory::getImageClient($iZettleClient);

        $image = $imageClient->postImage(new ImageUrlUpload(''));

        self::assertInstanceOf(Image::class, $image);
    }
}
