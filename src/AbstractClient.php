<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi;

use DateTime;
use GuzzleHttp\ClientInterface;
use LauLamanApps\IzettleApi\Client\AccessToken;
use LauLamanApps\IzettleApi\Client\Exceptions\AccessTokenExpiredException;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractClient
{
    private $guzzleClient;
    private $accessToken;
    private $organizationUuid = 'self';

    public function __construct(ClientInterface $guzzleClient, AccessToken $accessToken)
    {
        $this->guzzleClient = $guzzleClient;
        $this->accessToken = $accessToken;
        $this->validateAccessToken();
    }

    public function setOrganizationUuid(UuidInterface $organizationUuid): void
    {
        $this->organizationUuid = (string) $organizationUuid;
    }

    protected function getOrganizationUuid(): string
    {
        return $this->organizationUuid;
    }


    protected function get(string $url, ?array $queryParameters = null): ResponseInterface
    {
        $options =  array_merge(['headers' => $this->getAuthorizationHeader()], ['query' => $queryParameters]);

        return $this->guzzleClient->get($url, $options);
    }

    protected function post(string $url, string $data): void
    {
        $headers = array_merge(
            $this->getAuthorizationHeader(),
            [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $options =  array_merge(['headers' => $headers], ['body' => $data]);

        $this->guzzleClient->post($url, $options);
    }

    protected function delete(string $url): void
    {
        $this->guzzleClient->delete($url, ['headers' => $this->getAuthorizationHeader()]);
    }

    protected function getAuthorizationHeader(): array
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
}
