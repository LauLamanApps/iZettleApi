<?php

namespace LauLamanApps\IzettleApi\Client\Inventory;

use LauLamanApps\IzettleApi\API\Inventory\LocationInventory;

interface LocationInventoryBuilderInterface
{
    /**
     * @return LocationInventory[]
     */
    public function buildFromJsonArray(string $json): array;

    public function buildFromJson(string $json): LocationInventory;
}
