<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Inventory;

use LauLamanApps\IzettleApi\API\Inventory\Location\TypeEnum;
use LauLamanApps\IzettleApi\API\Inventory\LocationBalance;
use Ramsey\Uuid\Uuid;

final class LocationBalanceBuilder implements LocationBalanceBuilderInterface
{
    public function buildFromArray(array $data): LocationBalance
    {
        return new LocationBalance(
            Uuid::fromString($data['locationUuid']),
            TypeEnum::get($data['locationType']),
            Uuid::fromString($data['productUuid']),
            Uuid::fromString($data['variantUuid']),
            $data['balance']
        );
    }
}
