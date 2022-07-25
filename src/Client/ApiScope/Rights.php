<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\ApiScope;

use Werkspot\Enum\AbstractEnum;

enum Rights: string
{
    case READ = 'READ';
    case WRITE = 'WRITE';
}
