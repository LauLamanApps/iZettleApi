<?php

namespace LauLamanApps\IzettleApi\Client\Inventory;

use LauLamanApps\IzettleApi\API\Inventory\ProductBalance;

interface ProductBalanceBuilderInterface
{
    public function buildFromJson(string $json): ProductBalance;
}
