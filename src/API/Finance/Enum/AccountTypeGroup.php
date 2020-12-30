<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static AccountTypeGroup LIQUID()
 * @method static AccountTypeGroup PRELIMINARY()
 */
final class AccountTypeGroup extends Enum
{
    public const LIQUID = 'LIQUID';
    public const PRELIMINARY = 'PRELIMINARY';
}
