<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Purchase;

use Money\Money;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractPayment
{
    private $uuid;
    private $amount;

    public function __construct(UuidInterface $uuid, Money $amount)
    {
        $this->uuid = $uuid;
        $this->amount = $amount;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }
}
