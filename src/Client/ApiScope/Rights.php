<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\ApiScope;

use Werkspot\Enum\AbstractEnum;

final class Rights extends AbstractEnum
{
    public const READ = 'READ';
    public const WRITE = 'WRITE';
}
