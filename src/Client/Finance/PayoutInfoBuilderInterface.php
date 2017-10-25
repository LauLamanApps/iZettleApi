<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Finance;

use LauLamanApps\IzettleApi\API\Finance\PayoutInfo;

interface PayoutInfoBuilderInterface
{
    public function buildFromJson(string $json): PayoutInfo;
}
