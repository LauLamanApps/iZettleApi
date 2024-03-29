<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;

interface PurchaseHistoryBuilderInterface
{
    public function buildFromJson(string $jsonData): PurchaseHistory;
}
