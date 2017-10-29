<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use LauLamanApps\IzettleApi\Client\AccessToken;
use LauLamanApps\IzettleApi\GuzzleIzettleClient;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @small
 */
final class GuzzleIzettleClientTest extends TestCase
{
    use MockeryAssertionTrait;

    const ACCESS_TOKEN = 'access-token';

    /**
     * @test
     * @dataProvider getGetData
     */
    public function get($url, $queryParameters)
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::ACCESS_TOKEN
            ],
            'query' => $queryParameters
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClientInterface::class);
        $guzzleClientMock->shouldReceive('get')->with($url, $options)->once()->andReturn(Mockery::mock(ResponseInterface::class));

        $izettleClient = new GuzzleIzettleClient($guzzleClientMock, $this->getAccessToken());
        $izettleClient->get($url, $queryParameters);
    }

    public function getGetData(): array
    {
        return [
            ['example.com', null],
            ['example.com/link', ['query1' => 'this-is-one']],
            ['example.com/another-link', ['query1' => 'this-is-one', 'query2' => 'this-is-two']],
        ];
    }

    /**
     * @test
     * @dataProvider getPostData
     */
    public function post($url, $data)
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::ACCESS_TOKEN,
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $data,
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClientInterface::class);
        $guzzleClientMock->shouldReceive('post')->with($url, $options)->once();

        $izettleClient = new GuzzleIzettleClient($guzzleClientMock, $this->getAccessToken());
        $izettleClient->post($url, $data);
    }

    public function getPostData(): array
    {
        return [
            ['example.com/account', json_encode(['name' => 'John Doe'])],
            ['example.com/account/names', json_encode(['firstName' => 'john', 'lastName' => 'Doe'])],
        ];
    }

    /**
     * @test
     * @dataProvider getPutData
     */
    public function put($url, $data)
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::ACCESS_TOKEN,
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $data,
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClientInterface::class);
        $guzzleClientMock->shouldReceive('put')->with($url, $options)->once();

        $izettleClient = new GuzzleIzettleClient($guzzleClientMock, $this->getAccessToken());
        $izettleClient->put($url, $data);
    }

    public function getPutData(): array
    {
        return [
            ['example.com/account', json_encode(['name' => 'John Doe'])],
            ['example.com/account/names', json_encode(['firstName' => 'john', 'lastName' => 'Doe'])],
        ];
    }

    /**
     * @test
     * @dataProvider getDeleteData
     */
    public function delete($url)
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::ACCESS_TOKEN,
            ],
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClientInterface::class);
        $guzzleClientMock->shouldReceive('delete')->with($url, $options)->once();

        $izettleClient = new GuzzleIzettleClient($guzzleClientMock, $this->getAccessToken());
        $izettleClient->delete($url);
    }

    public function getDeleteData(): array
    {
        return [
            ['example.com/delete/some-product/1'],
        ];
    }

    /**
     * @test
     */
    public function getJson()
    {
        $data = 'getJsonTest';
        $responseMock =  Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive('getBody')->once()->andReturnSelf();
        $responseMock->shouldReceive('getContents')->once()->andReturn($data);

        $izettleClient = new GuzzleIzettleClient(new GuzzleClient(), $this->getAccessToken());
        $returnedData = $izettleClient->getJson($responseMock);

        self::assertSame($data, $returnedData);
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Exceptions\AccessTokenExpiredException
     */
    public function validateAccessToken()
    {
        $invalidAccessToken =  new AccessToken('', new DateTime('-1 day'), '');

        new GuzzleIzettleClient(new GuzzleClient(), $invalidAccessToken);
    }

    protected function getAccessToken() : AccessToken
    {
        return new AccessToken(self::ACCESS_TOKEN, new DateTime('+ 1 day'), '');
    }
}
