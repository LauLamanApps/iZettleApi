<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Inventory;

use LauLamanApps\IzettleApi\API\Inventory\LocationBalance;

interface LocationBalanceBuilderInterface
{
    public function buildFromArray(array $data): LocationBalance;
}
