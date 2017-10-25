<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Integration\Client;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use LauLamanApps\IzettleApi\Client\AccessToken;
use LauLamanApps\IzettleApi\GuzzleIzettleClient;
use PHPUnit\Framework\TestCase;

abstract class AbstractClientTest extends TestCase
{
    const CLIENT_ID = 'clientId';
    const CLIENT_SECRET = 'clientSecret';

    protected function getGuzzleIzettleClient(int $status, string $body): GuzzleIzettleClient
    {
        $mock = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);

        $izettleClient = new GuzzleIzettleClient(new GuzzleClient(['handler' => $handler]), self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($this->getAccessToken());

        return $izettleClient;
    }

    private function getAccessToken() : AccessToken
    {
        return new AccessToken('', new DateTime('+ 1 day'), '');
    }
}
