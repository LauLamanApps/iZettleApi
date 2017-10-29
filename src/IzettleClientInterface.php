<?php

namespace LauLamanApps\IzettleApi;

use LauLamanApps\IzettleApi\Client\AccessToken;
use Psr\Http\Message\ResponseInterface;

interface IzettleClientInterface
{
    const API_ACCESS_TOKEN_REQUEST_URL = 'https://oauth.izettle.net/token';
    const API_ACCESS_TOKEN_PASSWORD_GRANT = 'password';
    const API_ACCESS_TOKEN_REFRESH_TOKEN_URL = 'https://oauth.izettle.net/token';
    const API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT = 'refresh_token';

    public function setAccessToken(AccessToken $accessToken): void;

    public function getAccessTokenFromUserLogin(string $username, string $password): AccessToken;

    public function refreshAccessToken(?AccessToken $accessToken =  null): AccessToken;

    public function get(string $url, ?array $queryParameters = null): ResponseInterface;

    public function post(string $url, string $jsonData): void;

    public function put(string $url, string $jsonData): void;

    public function delete(string $url): void;

    public function getJson(ResponseInterface $response): string;
}
