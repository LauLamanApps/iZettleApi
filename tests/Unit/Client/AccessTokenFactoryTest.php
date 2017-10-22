<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Client;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use LauLamanApps\iZettleApi\Client\AccessToken;
use LauLamanApps\iZettleApi\Client\AccessTokenFactory;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class AccessTokenFactoryTest extends TestCase
{
    const CLIENT_ID = 'clientId';
    const CLIENT_SECRET = 'clientSecret';

    /**
     * @test
     */
    public function getFromUserLogin(): void
    {
        $accessToken = 'accessToken';
        $refreshToken = 'refreshToken';
        $expiresIn = 7200;
        $username ='username';
        $password ='password';
        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => AccessTokenFactory::API_ACCESS_TOKEN_PASSWORD_GRANT,
                'client_id' => self::CLIENT_ID,
                'client_secret' => self::CLIENT_SECRET,
                'username' => $username,
                'password' => $password
            ],
        ];

        $guzzleClientMock = $this->getGuzzleClient(
            AccessTokenFactory::API_ACCESS_TOKEN_REQUEST_URL,
            $options,
            $accessToken,
            $expiresIn,
            $refreshToken
        );

        $accessTokenFactory =  new AccessTokenFactory($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $accessTokenObject =  $accessTokenFactory->getFromUserLogin($username, $password);

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
    public function refresh(): void
    {
        $oldAccessToken = new AccessToken('accessToken', new DateTime(), 'refreshToken');
        $newAccessToken = 'accessToken2';
        $newRefreshToken = 'refreshToken2';
        $newExpiresIn = 7200;

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => AccessTokenFactory::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => self::CLIENT_ID,
                'client_secret' => self::CLIENT_SECRET,
                'refresh_token' => $oldAccessToken->getRefreshToken(),
            ],
        ];

        $guzzleClientMock = $this->getGuzzleClient(
            AccessTokenFactory::API_ACCESS_TOKEN_REQUEST_URL,
            $options,
            $newAccessToken,
            $newExpiresIn,
            $newRefreshToken
        );

        $accessTokenFactory =  new AccessTokenFactory($guzzleClientMock, self::CLIENT_ID, self::CLIENT_SECRET);
        $accessTokenObject =  $accessTokenFactory->refresh($oldAccessToken);

        self::assertSame($newAccessToken, $accessTokenObject->getToken());
        self::assertSame($newRefreshToken, $accessTokenObject->getRefreshToken());
        self::assertEquals(
            (new DateTime($newExpiresIn . ' second'))->format('Y-m-d H:i:s'),
            $accessTokenObject->getExpires()->format('Y-m-d H:i:s')
        );
    }

    private function getGuzzleClient(
        string $url,
        array $options,
        string $accessToken,
        int $expiresIn,
        string $refreshToken
    ): GuzzleClient {
        $guzzleClientMock = Mockery::mock(GuzzleClient::class);
        $guzzleClientMock->shouldReceive('request')->withArgs(['POST', $url, $options])->andReturnSelf();
        $guzzleClientMock->shouldReceive('getBody')->andReturnSelf();
        $guzzleClientMock->shouldReceive('getContents')->andReturn(json_encode(
            [
                'access_token' => $accessToken,
                'expires_in' => $expiresIn,
                'refresh_token' => $refreshToken
            ]
        ));

        return $guzzleClientMock;
    }
}
