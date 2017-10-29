<?php

namespace LauLamanApps\IzettleApi\Client\Purchase;

use Money\Currency;
use Money\Money;

interface VatBuilderInterface
{
    /**
     * @return Money[]
     */
    public function buildFromArray(array $vatAmounts, Currency $currency): array;
}
