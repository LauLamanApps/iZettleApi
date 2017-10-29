<?php

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\Coordinates;

interface CoordinatesBuilderInterface
{
    public function buildFromArray(array $coordinates): Coordinates;
}
