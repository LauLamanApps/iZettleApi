<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Purchase\Payment;

use LauLamanApps\IzettleApi\API\Purchase\AbstractPayment;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

final class CardOnlinePayment extends AbstractPayment
{
    private $referenceNumber;
    private $maskedPan;
    private $cardType;
    private $cardPaymentEntryMode;
    private $paymentlinkOrderUuid;

    public function __construct(
        UuidInterface $uuid,
        Money $amount,
        string $referenceNumber,
        string $maskedPan,
        string $cardType,
        string $cardPaymentEntryMode,
        UuidInterface $paymentlinkOrderUuid
    ) {
        parent::__construct($uuid, $amount);
        $this->referenceNumber = $referenceNumber;
        $this->maskedPan = $maskedPan;
        $this->cardType = $cardType;
        $this->cardPaymentEntryMode = $cardPaymentEntryMode;
        $this->paymentlinkOrderUuid = $paymentlinkOrderUuid;
    }

    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }

    public function getMaskedPan(): string
    {
        return $this->maskedPan;
    }

    public function getCardType(): string
    {
        return $this->cardType;
    }

    public function getCardPaymentEntryMode(): string
    {
        return $this->cardPaymentEntryMode;
    }

    public function getPaymentlinkOrderUuid(): UuidInterface
    {
        return $this->paymentlinkOrderUuid;
    }
}
