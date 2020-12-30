<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\ApiScope;

use MyCLabs\Enum\Enum;

/**
 * @method static Rights READ()
 * @method static Rights WRITE()
 */
final class Rights extends Enum
{
    public const READ = 'READ';
    public const WRITE = 'WRITE';
}
