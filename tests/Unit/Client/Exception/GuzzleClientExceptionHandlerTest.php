<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Exception;

use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use LauLamanApps\IzettleApi\Client\Exception\ClientException;
use LauLamanApps\IzettleApi\Client\Exception\GuzzleClientExceptionHandler;
use LauLamanApps\IzettleApi\Client\Exception\InvalidClient\InvalidClientIdException;
use LauLamanApps\IzettleApi\Client\Exception\InvalidClientException;
use LauLamanApps\IzettleApi\Client\Exception\InvalidGrant\InvalidUsernameOrPasswordException;
use LauLamanApps\IzettleApi\Client\Exception\InvalidGrant\TooManyFailedAttemptsException;
use LauLamanApps\IzettleApi\Client\Exception\InvalidGrantException;
use LauLamanApps\IzettleApi\Client\Exception\NotFoundException;
use LauLamanApps\IzettleApi\Tests\Unit\MockeryAssertionTrait;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @small
 */
final class GuzzleClientExceptionHandlerTest extends TestCase
{
    use MockeryAssertionTrait;

    /**
     * @test
     *
     * @dataProvider getClientExceptions
     */
    public function handleClientException(GuzzleClientException $exception, string $expectedException): void
    {
        $this->expectException($expectedException);
        GuzzleClientExceptionHandler::handleClientException($exception);
    }

    /**
     * @return GuzzleClientException[]
     */
    public function getClientExceptions(): array
    {
        return [
            'undefined' => [new GuzzleClientException(0, Mockery::mock(RequestInterface::class)), ClientException::class],
            'INCORRECT_PASSWORD_OR_USERNAME' => [$this->getClientException(400, 'invalid_grant', 'INCORRECT_PASSWORD_OR_USERNAME'), InvalidUsernameOrPasswordException::class],
            'TOO_MANY_FAILED_ATTEMPTS' => [$this->getClientException(400, 'invalid_grant', 'TOO_MANY_FAILED_ATTEMPTS'), TooManyFailedAttemptsException::class],
            'InvalidGrantException' => [$this->getClientException(400, 'invalid_grant', '[fallback]'), InvalidGrantException::class],
            'Invalid client_id' => [$this->getClientException(400, 'invalid_client', 'Invalid client_id'), InvalidClientIdException::class],
            'Invalid client' => [$this->getClientException(400, 'invalid_client', '[fallback]'), InvalidClientException::class],
            'unauthorized_client' => [$this->getClientException(400, 'unauthorized_client', '[does not mather]'), InvalidClientException::class],
            'InvalidClientException' => [$this->getClientException(400, 'fallback', '[does not mather]'), InvalidClientException::class],

        ];
    }

    /**
     * @test
     * @dataProvider getRequestExceptions
     */
    public function handleRequestException(GuzzleRequestException $exception, string $expectedException): void
    {
        $this->expectException($expectedException);
        GuzzleClientExceptionHandler::handleRequestException($exception);
    }

    /**
     * @return GuzzleRequestException[]
     */
    public function getRequestExceptions(): array
    {
        return [
            'undefined' => [$this->getRequestException(0, '[fallback]'), ClientException::class],
            'not found' => [$this->getRequestException(404, 'not found'), NotFoundException::class],
        ];
    }

    private function getClientException(int $code, string $error, string $errorDescription): GuzzleClientException
    {
        /** @var RequestInterface|MockInterface $request */
        $request = Mockery::mock(RequestInterface::class);
        $response = $this->getResponse($code, ['error'=> $error, 'error_description' => $errorDescription]);

        return new GuzzleClientException('', $request, $response);
    }

    private function getRequestException(int $code, string $developerMessage): GuzzleRequestException
    {
        /** @var RequestInterface|MockInterface $request */
        $request = Mockery::mock(RequestInterface::class);
        $response = $this->getResponse($code, ['developerMessage' => $developerMessage]);

        return new GuzzleRequestException('', $request, $response);
    }

    private function getResponse(int $code, array $returnData): ResponseInterface
    {
        /** @var ResponseInterface|MockInterface $response */
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->once()->andReturn($code);
        $response->shouldReceive('getBody')->once()->andReturnSelf();
        $response->shouldReceive('getContents')->once()->andReturn(json_encode($returnData));

        return $response;
    }
}
