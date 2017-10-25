<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Finance;

use DateTime;
use LauLamanApps\IzettleApi\API\Finance\Enum\TransactionType;
use Ramsey\Uuid\UuidInterface;

final class AccountTransaction
{
    private $timestamp;
    private $amount;
    private $originatorTransactionType;
    private $originatingTransactionUuid;

    public function __construct(
        DateTime $timestamp,
        int $amount,
        TransactionType $originatorTransactionType,
        UuidInterface $originatingTransactionUuid
    ) {
        $this->timestamp = $timestamp;
        $this->amount = $amount;
        $this->originatorTransactionType = $originatorTransactionType;
        $this->originatingTransactionUuid = $originatingTransactionUuid;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getOriginatorTransactionType(): TransactionType
    {
        return $this->originatorTransactionType;
    }

    public function getOriginatingTransactionUuid(): UuidInterface
    {
        return $this->originatingTransactionUuid;
    }
}
