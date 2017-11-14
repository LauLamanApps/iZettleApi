<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Purchase\Exception;

use Exception;
use LauLamanApps\IzettleApi\Exception\IzettleApiException;

final class InvalidLongitudeException extends Exception implements IzettleApiException
{
}
