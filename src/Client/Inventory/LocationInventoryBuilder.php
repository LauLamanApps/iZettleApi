<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Inventory;

use LauLamanApps\IzettleApi\API\Inventory\LocationInventory;

final class LocationInventoryBuilder implements LocationInventoryBuilderInterface
{
    /**
     * @var LocationBalanceBuilderInterface
     */
    private $locationBalanceBuilder;

    public function __construct(LocationBalanceBuilderInterface $locationBalanceBuilder)
    {
        $this->locationBalanceBuilder = $locationBalanceBuilder;
    }

    /**
     * @return LocationInventory[]
     */
    public function buildFromJsonArray(string $json): array
    {
        $data = json_decode($json, true);

        $locationInventories = [];

        foreach ($data as $locationInventoryData){
            $locationInventories[] = $this->build($locationInventoryData);
        }

        return $locationInventories;
    }

    public function buildFromJson(string $json): LocationInventory
    {
        return $this->build(json_decode($json, true));
    }

    private function build($data): LocationInventory
    {
        $locationBalances = [];
        foreach ($data['variants'] as $locationBalanceData) {
            $categories[] = $this->locationBalanceBuilder->buildFromArray($locationBalanceData);
        }

        return new LocationInventory($data['uuid'],  $data['trackedProducts'], ...$locationBalances);
    }
}
