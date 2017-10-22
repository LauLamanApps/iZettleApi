<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Client;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;

final class AccessTokenFactory
{
    const API_ACCESS_TOKEN_REQUEST_URL = 'https://oauth.izettle.net/token';
    const API_ACCESS_TOKEN_PASSWORD_GRANT = 'password';
    const API_ACCESS_TOKEN_REFRESH_TOKEN_URL = 'https://oauth.izettle.net/token';
    const API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT = 'refresh_token';

    private $guzzleClient;
    private $clientId;
    private $clientSecret;

    public function __construct(GuzzleClient $guzzleClient, string $clientId, string $clientSecret)
    {
        $this->guzzleClient = $guzzleClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function getFromUserLogin(string $username, string $password): AccessToken
    {
        $options = [
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_PASSWORD_GRANT,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $username,
                'password' => $password
            ],
        ];

        return $this->getAccessTokenFromData($this->makeRequest(self::API_ACCESS_TOKEN_REQUEST_URL, $options));
    }

    public function refresh(AccessToken $accessToken): AccessToken
    {
        $options = [
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $accessToken->getRefreshToken()
            ],
        ];

        return $this->getAccessTokenFromData($this->makeRequest(self::API_ACCESS_TOKEN_REFRESH_TOKEN_URL, $options));
    }

    private function makeRequest($url, $options): array
    {
        $headers = ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded']];
        $options = array_merge($headers, $options);

        $response = $this->guzzleClient->request('POST', $url, $options);
        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    private function getAccessTokenFromData(array $data): AccessToken
    {
        return new AccessToken(
            $data['access_token'],
            new DateTime(sprintf('+%d second', $data['expires_in'])),
            $data['refresh_token']
        );
    }
}
