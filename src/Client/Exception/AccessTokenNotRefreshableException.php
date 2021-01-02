<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Exception;

use Exception;
use LauLamanApps\IzettleApi\Exception\IzettleApiException;
use RuntimeException;

final class AccessTokenNotRefreshableException extends RuntimeException implements IzettleApiException
{
}
