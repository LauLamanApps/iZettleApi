<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use Werkspot\Enum\AbstractEnum;

final class AccountTypeGroup extends AbstractEnum
{
    const LIQUID = 'LIQUID';
    const PRELIMINARY = 'PRELIMINARY';
}
