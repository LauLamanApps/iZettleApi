<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Exception;

use Exception;

final class UnprocessableEntityException extends Exception implements IzettleApiException
{
    /**
     * @var string|null
     */
    private $errorType;

    /**
     * @var array|null
     */
    private $violations;

    public function __construct(string $json)
    {
        $error = json_decode($json, true);

        parent::__construct($error['developerMessage'], 422);

        $this->errorType = $error['errorType'];
        $this->violations = $error['violations'];
    }

    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    public function getViolations(): ?array
    {
        return $this->violations;
    }
}
