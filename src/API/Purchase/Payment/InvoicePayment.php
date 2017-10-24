<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Purchase\Payment;

use LauLamanApps\IzettleApi\API\Purchase\AbstractPayment;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

final class InvoicePayment extends AbstractPayment
{
    /**
     * On the time of development there was no information available about
     * the data that is returned for this payment Type. The documentation
     * only mention the types. Thats why i created the payment types as
     * Class but was unable to implement it.
     *
     * Feel free to open a PR to complete this
     */
    public function __construct(
        UuidInterface $uuid,
        Money $amount
    ) {
        parent::__construct($uuid, $amount);

        throw new \Exception('Payment type not implemented');
    }
}
