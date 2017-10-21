<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Purchase;

use DateTime;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

final class Purchase
{
    private $Uuid;
    private $Uuid1;
    private $timestamp;
    private $coordinates;
    private $country;
    private $user;
    private $organizationId;
    private $purchaseNumber;
    private $amount;
    private $vatAmount;
    private $products;
    private $payments;
    private $vatAmounts;
    private $receiptCopyAllowed;
    private $published;
    private $refund;
    private $refunded;

    public function __construct(
        string $Uuid,
        UuidInterface $Uuid1,
        DateTime $timestamp,
        ?Coordinates $coordinates = null,
        string $country,
        User $user,
        int $organizationId,
        int $purchaseNumber,
        Money $amount,
        Money $vatAmount,
        $products,
        $payments,
        $vatAmounts,
        bool $receiptCopyAllowed,
        ?bool $published = null,
        bool $refund,
        bool $refunded
    ) {
        $this->Uuid = $Uuid;
        $this->Uuid1 = $Uuid1;
        $this->timestamp = $timestamp;
        $this->coordinates = $coordinates;
        $this->country = $country;
        $this->user = $user;
        $this->organizationId = $organizationId;
        $this->purchaseNumber = $purchaseNumber;
        $this->amount = $amount;
        $this->vatAmount = $vatAmount;
        $this->products = $products;
        $this->payments = $payments;
        $this->vatAmounts = $vatAmounts;
        $this->receiptCopyAllowed = $receiptCopyAllowed;
        $this->published = $published;
        $this->refund = $refund;
        $this->refunded = $refunded;
    }

    public function getUuid(): string
    {
        return $this->Uuid;
    }

    public function getUuid1(): UuidInterface
    {
        return $this->Uuid1;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getOrganizationId(): int
    {
        return $this->organizationId;
    }

    public function getPurchaseNumber(): int
    {
        return $this->purchaseNumber;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getVatAmount(): Money
    {
        return $this->vatAmount;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function getVatAmounts()
    {
        return $this->vatAmounts;
    }

    public function isReceiptCopyAllowed(): bool
    {
        return $this->receiptCopyAllowed;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function isRefund(): bool
    {
        return $this->refund;
    }

    public function isRefunded(): bool
    {
        return $this->refunded;
    }
}
