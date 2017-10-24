<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Purchase\Payment;

use LauLamanApps\IzettleApi\API\Purchase\AbstractPayment;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

final class CashPayment extends AbstractPayment
{
    private $handedAmount;

    public function __construct(UuidInterface $uuid, Money $amount, Money $handedAmount)
    {
        parent::__construct($uuid, $amount);
        $this->handedAmount = $handedAmount;
    }

    public function getHandedAmount(): Money
    {
        return $this->handedAmount;
    }

    public function getChangeAmount(): Money
    {
        return $this->handedAmount->subtract($this->getAmount());
    }
}
