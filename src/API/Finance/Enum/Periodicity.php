<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use Werkspot\Enum\AbstractEnum;

final class Periodicity extends AbstractEnum
{
    const DAILY = 'DAILY';
    const WEEKLY = 'WEEKLY';
    const MONTHLY = 'MONTHLY';
}
