<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use Werkspot\Enum\AbstractEnum;

/**
 * @method static Periodicity daily()
 * @method bool isDaily()
 * @method static Periodicity weekly()
 * @method bool isWeekly()
 * @method static Periodicity monthly()
 * @method bool isMonthly()
 */
final class Periodicity extends AbstractEnum
{
    public const DAILY = 'DAILY';
    public const WEEKLY = 'WEEKLY';
    public const MONTHLY = 'MONTHLY';
}
