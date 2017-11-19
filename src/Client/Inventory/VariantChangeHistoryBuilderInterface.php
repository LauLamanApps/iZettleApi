<?php

namespace LauLamanApps\IzettleApi\Client\Inventory;

use LauLamanApps\IzettleApi\API\Inventory\VariantChangeHistory;

interface VariantChangeHistoryBuilderInterface
{
    /**
     * @return VariantChangeHistory[]
     */
    public function buildFromJson(string $json): array;
}
