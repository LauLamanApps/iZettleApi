<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Finance;

use LauLamanApps\IzettleApi\API\Finance\Enum\Periodicity;
use LauLamanApps\IzettleApi\API\Finance\PayoutInfo;
use Money\Currency;
use Money\Money;
use Psr\Http\Message\ResponseInterface;

final class PayoutInfoBuilder implements PayoutInfoBuilderInterface
{
    public function buildFromJson(string $json): PayoutInfo
    {
        $data = json_decode($json, true)['data'];

        $currency = new Currency($data['currencyId']);

        return new PayoutInfo(
            new Money($data['totalBalance'], $currency),
            new Money($data['nextPayoutAmount'], $currency),
            new Money($data['discountRemaining'], $currency),
            Periodicity::get($data['periodicity'])
        );
    }
}
