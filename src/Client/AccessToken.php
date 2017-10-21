<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Client;

use DateInterval;
use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

final class AccessToken
{
    const API_ACCESS_TOKEN_REQUEST_URL = 'https://oauth.izettle.net/token';
    const API_ACCESS_TOKEN_PASSWORD_GRANT = 'password';

    const API_ACCESS_TOKEN_REFRESH_TOKEN_URL = 'https://oauth.izettle.net/token';
    const API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT = 'refresh_token';

    /** @var GuzzleClientInterface */
    private static $guzzleClient;
    private static $clientId;
    private static $clientSecret;

    private $accessToken;
    private $expires;
    private $refreshToken;

    public function __construct(string $accessToken, DateTime $expires, string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->expires = $expires;
        $this->refreshToken = $refreshToken;
    }

    public static function getFromUserLogin(
        string $clientId,
        string $clientSecret,
        string $username,
        string $password,
        ?GuzzleClientInterface $guzzleClient = null
    ): self {
        self::$guzzleClient = $guzzleClient ? $guzzleClient : new GuzzleClient();

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_PASSWORD_GRANT,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'username' => $username,
                'password' => $password
            ],
        ];
        $self = self::requestToken(self::API_ACCESS_TOKEN_REQUEST_URL, $options);
        $self::$clientId = $clientId;
        $self::$clientSecret = $clientSecret;

        return $self;
    }

    public static function refreshWithToken(string $refreshToken, string $clientId, string $clientSecret, ?GuzzleClientInterface $guzzleClient = null): self
    {
        self::$guzzleClient = $guzzleClient ? $guzzleClient : new GuzzleClient();

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken
            ],
        ];

        $self = self::requestToken(self::API_ACCESS_TOKEN_REFRESH_TOKEN_URL, $options);
        $self::$clientId = $clientId;
        $self::$clientSecret = $clientSecret;

        return $self;
    }

    public function refresh(?GuzzleClientInterface $guzzleClient): void
    {
        self::$guzzleClient = $guzzleClient ? $guzzleClient : new GuzzleClient();

        $options = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => self::$clientId,
                'client_secret' => self::$clientSecret,
                'refresh_token' => $this->refreshToken
            ],
        ];

        $self = self::requestToken(self::API_ACCESS_TOKEN_REFRESH_TOKEN_URL, $options);

        $this->accessToken =  $self->getToken();
        $this->refreshToken = $self->getRefreshToken();
        $this->expires = $self->getExpires();
    }

    public function __toString(): string
    {
        return $this->getToken();
    }

    public function getToken(): string
    {
        return $this->accessToken;
    }

    public function getExpires(): DateTime
    {
        return $this->expires;
    }

    public function isExpired(): bool
    {
        return (new DateTime()) > $this->expires;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    private static function requestToken(string $url, array $options)
    {
        $response = self::$guzzleClient->request('POST', $url, $options);
        $data = json_decode($response->getBody()->getContents(), true);
        $expirationTime = self::calculateExpirationDateTime($data['expires_in']);

        return new self($data['access_token'], $expirationTime, $data['refresh_token']);
    }

    private static function calculateExpirationDateTime(int $expiresIn): DateTime
    {
        $dateInterval = new DateInterval(sprintf('PT%dS', $expiresIn));
        $expires =  new DateTime();
        $expires->add($dateInterval);

        return $expires;
    }
}
