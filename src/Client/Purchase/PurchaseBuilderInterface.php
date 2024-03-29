<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\Purchase;

interface PurchaseBuilderInterface
{
    public function buildFromArray(array $purchases): array;
    public function buildFromJson(string $jsonData): Purchase;
}
