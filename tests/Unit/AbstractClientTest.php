<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use LauLamanApps\IzettleApi\AbstractClient;
use LauLamanApps\IzettleApi\Client\AccessToken;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

class TestClassForAbstractClient extends AbstractClient
{
}

abstract class AbstractClientTest extends TestCase
{
    const ACCESS_TOKEN = 'access-token';
    const DEFAULT_ORGANIZATION_UUID = 'self';

    /**
     * @test
     */
    public function setOrganizationUuid()
    {
        $newOrganizationUuid = Uuid::uuid1();
        $abstractClient = new TestClassForAbstractClient(new GuzzleClient(), $this->getAccessToken());

        self::assertAttributeEquals(self::DEFAULT_ORGANIZATION_UUID, 'organizationUuid', $abstractClient);

        $abstractClient->setOrganizationUuid($newOrganizationUuid);

        self::assertAttributeEquals((string) $newOrganizationUuid, 'organizationUuid', $abstractClient);
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Exceptions\AccessTokenExpiredException
     */
    public function validateAccessToken()
    {
        $invalidAccessToken =  new AccessToken('', new DateTime('-1 day'), '');

        new TestClassForAbstractClient(new GuzzleClient(), $invalidAccessToken);
    }

    protected function getGuzzleClient(
        string $method,
        string $url,
        array $options,
        ?array $return = []
    ): GuzzleClient {
        $guzzleResponseMock = Mockery::mock(ResponseInterface::class);
        $guzzleResponseMock->shouldReceive('getBody')->andReturnSelf();
        $guzzleResponseMock->shouldReceive('getContents')->andReturn(json_encode($return));

        $guzzleClientMock = Mockery::mock(GuzzleClient::class);
        $guzzleClientMock->shouldReceive($method)->withArgs([$url, $options])->andReturn($guzzleResponseMock);

        return $guzzleClientMock;
    }

    protected function getAccessToken() : AccessToken
    {
        return new AccessToken(self::ACCESS_TOKEN, new DateTime('+ 1 day'), '');
    }
}
