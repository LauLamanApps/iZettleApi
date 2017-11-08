<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client;

use LauLamanApps\IzettleApi\API\Image;
use LauLamanApps\IzettleApi\Client\Image\ImageUploadRequestInterface;
use LauLamanApps\IzettleApi\Client\Universal\ImageBuilderInterface;
use LauLamanApps\IzettleApi\IzettleClientInterface;
use Ramsey\Uuid\UuidInterface;

final class ImageClient
{
    const BASE_URL = 'https://image.izettle.com/organizations/%s';
    const POST_IMAGE = self::BASE_URL . '/products';

    private $client;
    private $organizationUuid = 'self';
    private $imageBuilder;

    public function __construct(
        IzettleClientInterface $client,
        ?UuidInterface $organizationUuid = null,
        ImageBuilderInterface $imageBuilder
    ) {
        $this->client = $client;
        $this->organizationUuid = (string) $organizationUuid;
        $this->imageBuilder = $imageBuilder;
    }

    public function postImage(ImageUploadRequestInterface $imageUploadRequest): Image
    {
        $url = sprintf(self::POST_IMAGE, $this->organizationUuid);
        $data = json_encode($imageUploadRequest->getUploadRequest());

        $response = $this->client->post($url, $data);

        return $this->imageBuilder->buildFromJson($this->client->getJson($response));
    }
}
