<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use Money\Currency;
use Money\Money;

final class VatParser
{
    /**
     * @return Money[]
     */
    public static function parseArray(array $vatAmounts, Currency $currency): array
    {
        $data = [];
        foreach ($vatAmounts as $vat => $amount) {
            $data[$vat] = new Money($amount, $currency);
        }

        return $data;
    }
}
