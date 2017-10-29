<?php

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\AbstractPayment;
use Money\Currency;

interface PaymentBuilderInterface
{
    /**
     * @return AbstractPayment[]
     */
    public function buildFromArray(array $payments, Currency $currency): array;

    public function build(array $payment, Currency $currency): AbstractPayment;
}
