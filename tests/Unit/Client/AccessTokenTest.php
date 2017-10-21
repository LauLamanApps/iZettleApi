<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Client;

use DateTime;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use LauLamanApps\iZettleApi\Client\AccessToken;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class AccessTokenTest extends TestCase
{
    /**
     * @test
     */
    public function getFromUserLogin(): void
    {
        $accessToken = 'accessToken';
        $refreshToken = 'refreshToken';
        $expiresIn = 7200;
        $clientId ='clientId';
        $clientSecret ='clientSecret';
        $username ='username';
        $password ='password';
        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => AccessToken::API_ACCESS_TOKEN_PASSWORD_GRANT,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'username' => $username,
                'password' => $password
            ],
        ];
        $guzzleClientMock = $this->getGuzzleClient(AccessToken::API_ACCESS_TOKEN_REQUEST_URL, $options, $accessToken, $expiresIn, $refreshToken);

        $object = AccessToken::getFromUserLogin(
            $clientId,
            $clientSecret,
            $username,
            $password,
            $guzzleClientMock
        );

        self::assertSame($accessToken, (string) $object);
        self::assertSame($accessToken, $object->getToken());
        self::assertSame($refreshToken, $object->getRefreshToken());
        self::assertEquals(
            (new DateTime($expiresIn . ' second'))->format('Y-m-d H:i:s'),
            $object->getExpires()->format('Y-m-d H:i:s')
        );

        $accessToken2 = 'accessToken2';
        $expiresIn2 = 123;
        $refreshToken2 = 'refreshToken2';

        $refreshOptions = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => AccessToken::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken
            ],
        ];
        $guzzleClientMock2 = $this->getGuzzleClient(AccessToken::API_ACCESS_TOKEN_REFRESH_TOKEN_URL, $refreshOptions, $accessToken2, $expiresIn2, $refreshToken2);
        $object->refresh($guzzleClientMock2);

        self::assertSame($accessToken2, $object->getToken());
        self::assertSame($refreshToken2, $object->getRefreshToken());
        self::assertEquals(
            (new DateTime($expiresIn2 . ' second'))->format('Y-m-d H:i:s'),
            $object->getExpires()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function refreshWithToken(): void
    {
        $clientId = 'clientId';
        $clientSecret = 'clientSecret';
        $accessToken = 'accessToken';
        $refreshToken = 'refreshToken';
        $expiresIn = 0;
        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => AccessToken::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken
            ],
        ];
        $guzzleClientMock = $this->getGuzzleClient(AccessToken::API_ACCESS_TOKEN_REFRESH_TOKEN_URL, $options, $accessToken, $expiresIn, $refreshToken);

        $object = AccessToken::refreshWithToken(
            $refreshToken,
            $clientId,
            $clientSecret,
            $guzzleClientMock
        );

        self::assertTrue($object->isExpired());
    }

    private function getGuzzleClient(string $url, array $options, string $accessToken, int $expiresIn, string $refreshToken): GuzzleClientInterface
    {
        $guzzleClientMock = Mockery::mock(GuzzleClientInterface::class);
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
