<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client;

use LauLamanApps\IzettleApi\API\Image;
use LauLamanApps\IzettleApi\Client\Image\ImageFileUpload;
use LauLamanApps\IzettleApi\Client\Image\ImageUrlUpload;
use LauLamanApps\IzettleApi\Client\ImageClient;
use LauLamanApps\IzettleApi\Client\Universal\ImageBuilderInterface;
use Mockery;
use Mockery\MockInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class ImageClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function postImage_WithImageUrlUpload(): void
    {
        $organizationUuid = Uuid::uuid1();
        $imageUploadRequest =  new ImageUrlUpload('url');

        $url = sprintf(ImageClient::POST_IMAGE, $organizationUuid->toString());
        $postData = json_encode($imageUploadRequest->getUploadRequest());
        $data = json_encode(['postImage']);

        $izettleClientMock = $this->getIzettlePostMock($url, $postData);
        $izettleClientMock->shouldReceive('getJson')->once()->andReturn($data);

        list($imageBuilderMock) = $this->getDependencyMocks();
        $imageBuilderMock->shouldReceive('buildFromJson')->with($data)->once()->andReturn(new Image('image'));

        $financeClient = new ImageClient($izettleClientMock, $organizationUuid, $imageBuilderMock);
        $financeClient->postImage($imageUploadRequest);
    }

    /**
     * @test
     */
    public function postImage_WithImageFileUpload(): void
    {
        $organizationUuid = Uuid::uuid1();
        $file = dirname(__FILE__) . '/Image/files/50x50-good.png';
        $imageUploadRequest =  new ImageFileUpload($file);

        $url = sprintf(ImageClient::POST_IMAGE, $organizationUuid->toString());
        $postData = json_encode($imageUploadRequest->getUploadRequest());
        $data = json_encode(['postImage']);

        $izettleClientMock = $this->getIzettlePostMock($url, $postData);
        $izettleClientMock->shouldReceive('getJson')->once()->andReturn($data);

        list($imageBuilderMock) = $this->getDependencyMocks();
        $imageBuilderMock->shouldReceive('buildFromJson')->with($data)->once()->andReturn(new Image('image'));

        $financeClient = new ImageClient($izettleClientMock, $organizationUuid, $imageBuilderMock);
        $financeClient->postImage($imageUploadRequest);
    }

    /**
     * @return MockInterface[]
     */
    protected function getDependencyMocks(): array
    {
        return [
            Mockery::mock(ImageBuilderInterface::class),
        ];
    }
}
