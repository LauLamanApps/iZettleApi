<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Inventory;

use LauLamanApps\IzettleApi\API\Inventory\Location\TypeEnum;
use Ramsey\Uuid\UuidInterface;

final class LocationInventory
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var array
     */
    private $trackedProducts;

    /**
     * @var LocationBalance[]
     */
    private $locationBalances;

    public function __construct(UuidInterface $uuid, array $trackedProducts, LocationBalance ...$locationBalances)
    {
        $this->uuid = $uuid;
        $this->trackedProducts = $trackedProducts;
        $this->locationBalances = $locationBalances;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getTrackedProducts(): array
    {
        return $this->trackedProducts;
    }

    public function getLocationBalances(): array
    {
        return $this->locationBalances;
    }
}
