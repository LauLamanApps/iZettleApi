<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi;

use DateTime;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use LauLamanApps\IzettleApi\Client\AccessToken;
use LauLamanApps\IzettleApi\Client\Exceptions\AccessTokenExpiredException;
use LauLamanApps\IzettleApi\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;

class GuzzleIzettleClient implements IzettleClientInterface
{
    private $guzzleClient;
    private $clientId;
    private $clientSecret;
    private $accessToken;

    public function __construct(ClientInterface $guzzleClient, string $clientId, string $clientSecret)
    {
        $this->guzzleClient = $guzzleClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function setAccessToken(AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
        $this->validateAccessToken();
    }

    public function getAccessTokenFromUserLogin(string $username, string $password): AccessToken
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

        $this->setAccessToken($this->requestAccessToken(self::API_ACCESS_TOKEN_REQUEST_URL, $options));

        return $this->accessToken;
    }

    public function refreshAccessToken(?AccessToken $accessToken =  null): AccessToken
    {
        $accessToken = $accessToken ?? $this->accessToken;
        $options = [
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $accessToken->getRefreshToken()
            ],
        ];

        $this->setAccessToken($this->requestAccessToken(self::API_ACCESS_TOKEN_REFRESH_TOKEN_URL, $options));

        return $this->accessToken;
    }

    public function get(string $url, ?array $queryParameters = null): ResponseInterface
    {
        $options =  array_merge(['headers' => $this->getAuthorizationHeader()], ['query' => $queryParameters]);

        try {
            $response = $this->guzzleClient->get($url, $options);
        } catch (RequestException $e) {
            throw new NotFoundException($e->getMessage());
        }

        return $response;
    }

    public function post(string $url, string $jsonData): void
    {
        $headers = array_merge(
            $this->getAuthorizationHeader(),
            [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $options =  array_merge(['headers' => $headers], ['body' => $jsonData]);
        $this->guzzleClient->post($url, $options);
    }

    public function put(string $url, string $jsonData): void
    {
        $headers = array_merge(
            $this->getAuthorizationHeader(),
            [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $options =  array_merge(['headers' => $headers], ['body' => $jsonData]);

        $this->guzzleClient->put($url, $options);
    }

    public function delete(string $url): void
    {
        $this->guzzleClient->delete($url, ['headers' => $this->getAuthorizationHeader()]);
    }

    public function getJson(ResponseInterface $response): string
    {
        return $response->getBody()->getContents();
    }

    private function getAuthorizationHeader(): array
    {
        $this->validateAccessToken();

        return ['Authorization' => sprintf('Bearer %s', $this->accessToken->getToken())];
    }

    private function validateAccessToken(): void
    {
        if ($this->accessToken->isExpired()) {
            throw new AccessTokenExpiredException(
                sprintf(
                    'Access Token was valid till \'%s\' it\'s now \'%s\'',
                    $this->accessToken->getExpires()->format('Y-m-d H:i:s.u'),
                    (new DateTime())->format('Y-m-d H:i:s.u')
                )
            );
        }
    }

    private function requestAccessToken($url, $options): AccessToken
    {
        $headers = ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded']];
        $options = array_merge($headers, $options);

        $response = $this->guzzleClient->post($url, $options);
        $data = json_decode($response->getBody()->getContents(), true);

        return new AccessToken(
            $data['access_token'],
            new DateTime(sprintf('+%d second', $data['expires_in'])),
            $data['refresh_token']
        );
    }
}
