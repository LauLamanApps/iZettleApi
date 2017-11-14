<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client;

use DateTime;
use DateTimeImmutable;

final class AccessToken
{
    private $accessToken;
    private $expires;
    private $refreshToken;

    public function __construct(string $accessToken, DateTimeImmutable $expires, string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->expires = $expires;
        $this->refreshToken = $refreshToken;
    }

    public function getToken(): string
    {
        return $this->accessToken;
    }

    public function getExpires(): DateTimeImmutable
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
}
