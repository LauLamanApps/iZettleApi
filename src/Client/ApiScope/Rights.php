<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\ApiScope;

use Werkspot\Enum\AbstractEnum;

/**
 * @method static Rights read()
 * @method bool isRead()
 * @method static Rights write()
 * @method bool isWrite()
 */
final class Rights extends AbstractEnum
{
    public const READ = 'READ';
    public const WRITE = 'WRITE';
}
