<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance\Enum;

use Werkspot\Enum\AbstractEnum;

/**
 * @method static AccountTypeGroup liquid()
 * @method bool isLiquid()
 * @method static AccountTypeGroup preliminary()
 * @method bool isPreliminary()
 */
final class AccountTypeGroup extends AbstractEnum
{
    public const LIQUID = 'LIQUID';
    public const PRELIMINARY = 'PRELIMINARY';
}
