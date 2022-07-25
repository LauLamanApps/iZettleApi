<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use Werkspot\Enum\AbstractEnum;

enum Periodicity: string
{
    case DAILY = 'DAILY';
    case WEEKLY = 'WEEKLY';
    case MONTHLY = 'MONTHLY';
}
