<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Client\Purchase;

use LauLamanApps\iZettleApi\API\Purchase\Coordinates;

final class CoordinatesParser
{
    public static function parse(array $coordinates): Coordinates
    {
        return new Coordinates($coordinates['latitude'], $coordinates['longitude'], $coordinates['accuracyMeters']);
    }
}
