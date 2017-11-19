<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Inventory;

use Ramsey\Uuid\UuidInterface;

final class ProductBalance
{
    /**
     * @var UuidInterface
     */
    private $locationUuid;

    /**
     * @var LocationBalance[]
     */
    private $variants;
}
