<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit;

use DateTime;
use DateTimeImmutable;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use LauLamanApps\IzettleApi\API\Universal\IzettlePostable;
use LauLamanApps\IzettleApi\Client\AccessToken;
use LauLamanApps\IzettleApi\Client\ApiScope;
use LauLamanApps\IzettleApi\Client\Exception\AccessTokenNotRefreshableException;
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

    public const ACCESS_TOKEN = 'access-token';
    public const CLIENT_ID = 'clientId';
    public const CLIENT_SECRET = 'clientSecret';

    /**
     * @test
     */
    public function authoriseUserLogin(): void
    {
        $redirectUrl = 'example.com';
        $apiScope = new ApiScope();
        $expectedUrl = GuzzleIzettleClient::API_AUTHORIZE_USER_LOGIN_URL . sprintf(
            '?response_type=code&redirect_uri=%s&client_id=%s&scope=%s&state=oauth2',
            $redirectUrl,
            self::CLIENT_ID,
            $apiScope->getUrlParameters()
        );

        $accessTokenFactory = new GuzzleIzettleClient(Mockery::mock(GuzzleClient::class), self::CLIENT_ID, self::CLIENT_SECRET);
        $authoriseUserLoginUrl =  $accessTokenFactory->authoriseUserLogin($redirectUrl, $apiScope);

        self::assertSame($expectedUrl, $authoriseUserLoginUrl);
    }

    /**
     * @test
     */
    public function getAccessTokenFromUserLogin(): void
    {
        $accessToken = 'accessToken';
        $refreshToken = 'refreshToken';
        $expiresIn = 7200;
        $username ='username';
        $password ='password';
        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => GuzzleIzettleClient::API_ACCESS_TOKEN_PASSWORD_GRANT,
                'client_id' => self::CLIENT_ID,
                'client_secret' => self::CLIENT_SECRET,
                'username' => $username,
                'password' => $password,
            ],
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClient::class);
        $guzzleClientMock->shouldReceive('post')->withArgs([GuzzleIzettleClient::API_ACCESS_TOKEN_REQUEST_URL, $options])->once()->andReturnSelf();
        $guzzleClientMock->shouldReceive('getBody')->once()->andReturnSelf();
        $guzzleClientMock->shouldReceive('getContents')->once()->andReturn(json_encode(
            [
                'access_token' => $accessToken,
                'expires_in' => $expiresIn,
                'refresh_token' => $refreshToken,
            ]
        ));


        $accessTokenFactory = new GuzzleIzettleClient($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $accessTokenObject =  $accessTokenFactory->getAccessTokenFromUserLogin($username, $password);

        self::assertSame($accessToken, $accessTokenObject->getToken());
        self::assertSame($refreshToken, $accessTokenObject->getRefreshToken());
        self::assertEquals(
            (new DateTime($expiresIn . ' second'))->format('Y-m-d H:i:s'),
            $accessTokenObject->getExpires()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function getAccessTokenFromApiTokenAssertion(): void
    {
        $accessToken = 'accessToken';
        $expiresIn = 7200;
        $assertion = str_repeat('3a8ba448-69c2-4b9d-b8f3-5f8c2c04a2df', 32);
        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => GuzzleIzettleClient::API_ACCESS_ASSERTION_GRANT,
                'client_id' => self::CLIENT_ID,
                'assertion' => $assertion,
            ],
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClient::class);
        $guzzleClientMock->shouldReceive('post')->withArgs([GuzzleIzettleClient::API_ACCESS_TOKEN_REQUEST_URL, $options])->once()->andReturnSelf();
        $guzzleClientMock->shouldReceive('getBody')->once()->andReturnSelf();
        $guzzleClientMock->shouldReceive('getContents')->once()->andReturn(json_encode(
            [
                'access_token' => $accessToken,
                'expires_in' => $expiresIn,
            ]
        ));


        $accessTokenFactory = new GuzzleIzettleClient($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $accessTokenObject =  $accessTokenFactory->getAccessTokenFromApiTokenAssertion($assertion);

        self::assertSame($accessToken, $accessTokenObject->getToken());
        self::assertNull($accessTokenObject->getRefreshToken());
        self::assertEquals(
            (new DateTime($expiresIn . ' second'))->format('Y-m-d H:i:s'),
            $accessTokenObject->getExpires()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function refreshAccessToken(): void
    {
        $oldAccessToken = new AccessToken('accessToken', new DateTimeImmutable(), 'refreshToken');
        $newAccessToken = 'accessToken2';
        $newRefreshToken = 'refreshToken2';
        $newExpiresIn = 7200;

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => GuzzleIzettleClient::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => self::CLIENT_ID,
                'client_secret' => self::CLIENT_SECRET,
                'refresh_token' => $oldAccessToken->getRefreshToken(),
            ],
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClient::class);
        $guzzleClientMock->shouldReceive('post')->withArgs([GuzzleIzettleClient::API_ACCESS_TOKEN_REFRESH_TOKEN_URL, $options])->once()->andReturnSelf();
        $guzzleClientMock->shouldReceive('getBody')->once()->andReturnSelf();
        $guzzleClientMock->shouldReceive('getContents')->once()->andReturn(json_encode(
            [
                'access_token' => $newAccessToken,
                'expires_in' => $newExpiresIn,
                'refresh_token' => $newRefreshToken,
            ]
        ));

        $accessTokenFactory = new GuzzleIzettleClient($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $accessTokenObject = $accessTokenFactory->refreshAccessToken($oldAccessToken);

        self::assertSame($newAccessToken, $accessTokenObject->getToken());
        self::assertSame($newRefreshToken, $accessTokenObject->getRefreshToken());
        self::assertEquals(
            (new DateTime($newExpiresIn . ' second'))->format('Y-m-d H:i:s'),
            $accessTokenObject->getExpires()->format('Y-m-d H:i:s')
        );

        $fixedToken = new AccessToken('test', new DateTimeImmutable(), null);

        $this->expectException(AccessTokenNotRefreshableException::class);
        $accessTokenFactory->refreshAccessToken($fixedToken);
    }

    /**
     * @test
     * @dataProvider getGetData
     */
    public function get($url, $queryParameters): void
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::ACCESS_TOKEN,
            ],
            'query' => $queryParameters,
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClientInterface::class);
        $guzzleClientMock->shouldReceive('get')->with($url, $options)->once()->andReturn(Mockery::mock(ResponseInterface::class));

        $izettleClient = new GuzzleIzettleClient($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($this->getAccessToken());
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
    public function post($url, IzettlePostable $data): void
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::ACCESS_TOKEN,
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $data->getPostBodyData(),
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClientInterface::class);
        $guzzleClientMock->shouldReceive('post')->with($url, $options)->once()->andReturn(Mockery::mock(ResponseInterface::class));

        $izettleClient = new GuzzleIzettleClient($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($this->getAccessToken());
        $izettleClient->post($url, $data);
    }

    public function getPostData(): array
    {
        $postable = Mockery::mock(IzettlePostable::class);
        $postable->shouldReceive('getPostBodyData')->once();

        return [
            ['example.com/account', $postable],
            ['example.com/account/names', $postable],
        ];
    }

    /**
     * @test
     * @dataProvider getPutData
     */
    public function put($url, $data): void
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

        $izettleClient = new GuzzleIzettleClient($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($this->getAccessToken());
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
    public function delete($url): void
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::ACCESS_TOKEN,
            ],
        ];

        $guzzleClientMock = Mockery::mock(GuzzleClientInterface::class);
        $guzzleClientMock->shouldReceive('delete')->with($url, $options)->once();

        $izettleClient = new GuzzleIzettleClient($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($this->getAccessToken());
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
    public function getJson(): void
    {
        $data = 'getJsonTest';
        $responseMock =  Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive('getBody')->once()->andReturnSelf();
        $responseMock->shouldReceive('getContents')->once()->andReturn($data);

        $izettleClient = new GuzzleIzettleClient(new GuzzleClient(), self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($this->getAccessToken());
        $returnedData = $izettleClient->getJson($responseMock);

        self::assertSame($data, $returnedData);
    }

    /**
     * @test
     * @expectedException \LauLamanApps\IzettleApi\Client\Exception\AccessTokenExpiredException
     */
    public function validateAccessToken(): void
    {
        $invalidAccessToken = new AccessToken('', new DateTimeImmutable('-1 day'), '');

        $izettleClient = new GuzzleIzettleClient(new GuzzleClient(), self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($invalidAccessToken);
    }

    protected function getAccessToken(): AccessToken
    {
        return new AccessToken(self::ACCESS_TOKEN, new DateTimeImmutable('+ 1 day'), '');
    }
}
