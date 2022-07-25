<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Integration;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use LauLamanApps\IzettleApi\Client\Exception\InvalidGrant\InvalidUsernameOrPasswordException;
use LauLamanApps\IzettleApi\GuzzleIzettleClient;
use PHPUnit\Framework\TestCase;

/**
 * @medium
 */
final class GuzzleIzettleClientTest extends TestCase
{
    /**
     * @test
     */
    public function getAccessTokenFromUserLogin_WrongCredentials(): void
    {
        $this->expectException(InvalidUsernameOrPasswordException::class);

        $mock = new MockHandler([
            new ClientException(
                "wrong credentials",
                new Request('POST', 'example.com'),
                new Response(
                    400,
                    [],
                    file_get_contents(__DIR__ . '/json-files/getAccessTokenFromUserLogin_WrongCredentials.json')
                )
            ),
        ]);
        $handler = HandlerStack::create($mock);

        $izettleClient = new GuzzleIzettleClient(new GuzzleClient(['handler' => $handler]), '', '');
        $izettleClient->getAccessTokenFromUserLogin('', '');
    }
}
