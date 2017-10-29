<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\Coordinates;

final class CoordinatesBuilder implements CoordinatesBuilderInterface
{
    public function buildFromArray(array $coordinates): Coordinates
    {
        return new Coordinates($coordinates['latitude'], $coordinates['longitude'], $coordinates['accuracyMeters']);
    }
}
