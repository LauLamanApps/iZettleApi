<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi;

use LauLamanApps\IzettleApi\API\Universal\IzettlePostable;
use LauLamanApps\IzettleApi\Client\AccessToken;
use LauLamanApps\IzettleApi\Client\ApiScope;
use LauLamanApps\IzettleApi\Exception\UnprocessableEntityException;
use Psr\Http\Message\ResponseInterface;

interface IzettleClientInterface
{
    public const API_BASE_URL = 'https://oauth.zettle.com';
    public const API_AUTHORIZE_USER_LOGIN_URL = self::API_BASE_URL . '/authorize';

    public const API_ACCESS_TOKEN_REQUEST_URL = self::API_BASE_URL . '/token';
    public const API_ACCESS_TOKEN_PASSWORD_GRANT = 'password';
    public const API_ACCESS_TOKEN_CODE_GRANT = 'authorization_code';
    public const API_ACCESS_ASSERTION_GRANT = 'urn:ietf:params:oauth:grant-type:jwt-bearer';
    public const API_ACCESS_TOKEN_REFRESH_TOKEN_URL = self::API_BASE_URL . '/token';
    public const API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT = 'refresh_token';

    public function setAccessToken(AccessToken $accessToken): void;

    public function authoriseUserLogin(string $redirectUrl, ApiScope $apiScope): string;

    public function getAccessTokenFromUserLogin(string $username, string $password): AccessToken;

    public function refreshAccessToken(?AccessToken $accessToken =  null): AccessToken;

    public function get(string $url, ?array $queryParameters = null): ResponseInterface;

    /**
     * @throws UnprocessableEntityException
     */
    public function post(string $url, IzettlePostable $object): ResponseInterface;

    /**
     * @throws UnprocessableEntityException
     */
    public function put(string $url, string $jsonData): void;

    /**
     * @throws UnprocessableEntityException
     */
    public function delete(string $url): void;

    public function getJson(ResponseInterface $response): string;
}
