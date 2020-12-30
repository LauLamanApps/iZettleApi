<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static Periodicity DAILY()
 * @method static Periodicity WEEKLY()
 * @method static Periodicity MONTHLY()
 */
final class Periodicity extends Enum
{
    public const DAILY = 'DAILY';
    public const WEEKLY = 'WEEKLY';
    public const MONTHLY = 'MONTHLY';
}
