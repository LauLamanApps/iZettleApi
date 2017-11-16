<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Exception;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use LauLamanApps\IzettleApi\Client\Exception\ClientException as IzettleClientException;
use LauLamanApps\IzettleApi\Client\Exception\InvalidClient\InvalidClientIdException;
use LauLamanApps\IzettleApi\Client\Exception\InvalidGrant\InvalidUsernameOrPasswordException;
use LauLamanApps\IzettleApi\Client\Exception\InvalidGrant\TooManyFailedAttemptsException;

final class GuzzleClientExceptionHandler
{
    public static function handleClientException(ClientException $exception): void
    {
        switch ($exception->getCode()) {
            case 400:
                self::handleClient400Exception($exception);
        }

        throw new IzettleClientException($exception->getMessage());
    }

    public static function handleRequestException(RequestException $exception): void
    {
        $responseData = json_decode($exception->getResponse()->getBody()->getContents(), true);
        switch ($exception->getCode()) {
            case 404:
                throw new NotFoundException($responseData['developerMessage']);
        }

        throw new IzettleClientException($exception->getMessage());
    }

    private static function handleClient400Exception(ClientException $exception): void
    {
        $responseData = json_decode($exception->getResponse()->getBody()->getContents(), true);
        switch ($responseData['error']) {
            case 'invalid_grant':
                self::handleInvalidGrantException($responseData);
            case 'invalid_client':
                self::handleInvalidClientException($responseData);
            case 'unauthorized_client':
                throw new InvalidClientException($responseData['error_description']);
            default:
                throw new InvalidClientException($responseData['error_description']);
        }
    }

    private static function handleInvalidGrantException(array $responseData): void
    {
        switch ($responseData['error_description']) {
            case 'INCORRECT_PASSWORD_OR_USERNAME':
                throw new InvalidUsernameOrPasswordException();
            case 'TOO_MANY_FAILED_ATTEMPTS':
                throw new TooManyFailedAttemptsException();
            default:
                throw new InvalidGrantException($responseData['error_description']);
        }
    }

    private static function handleInvalidClientException(array $responseData): void
    {
        switch ($responseData['error_description']) {
            case 'Invalid client_id':
                throw new InvalidClientIdException();
            default:
                throw new InvalidClientException($responseData['error_description']);
        }
    }
}
