<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

enum AccountTypeGroup: string
{
    case LIQUID = 'LIQUID';
    case PRELIMINARY = 'PRELIMINARY';
}
