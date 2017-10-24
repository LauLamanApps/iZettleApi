<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Purchase\Payment;

use LauLamanApps\IzettleApi\API\Purchase\AbstractPayment;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

final class CardPayment extends AbstractPayment
{
    private $referenceNumber;
    private $maskedPan;
    private $cardType;
    private $cardPaymentEntryMode;
    private $applicationName;
    private $applicationIdentifier;
    private $terminalVerificationResults;
    private $nrOfInstallments;

    public function __construct(
        UuidInterface $uuid,
        Money $amount,
        string $referenceNumber,
        string $maskedPan,
        string $cardType,
        string $cardPaymentEntryMode,
        ?string $applicationName = null,
        ?string $applicationIdentifier = null,
        ?string $terminalVerificationResults = null,
        ?int $nrOfInstallments = null
    ) {
        parent::__construct($uuid, $amount);
        $this->referenceNumber = $referenceNumber;
        $this->maskedPan = $maskedPan;
        $this->cardType = $cardType;
        $this->cardPaymentEntryMode = $cardPaymentEntryMode;
        $this->applicationName = $applicationName;
        $this->applicationIdentifier = $applicationIdentifier;
        $this->terminalVerificationResults = $terminalVerificationResults;
        $this->nrOfInstallments = $nrOfInstallments;
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

    public function getApplicationName(): string
    {
        return $this->applicationName;
    }

    public function getApplicationIdentifier(): string
    {
        return $this->applicationIdentifier;
    }

    public function getTerminalVerificationResults(): string
    {
        return $this->terminalVerificationResults;
    }

    public function getNrOfInstallments(): int
    {
        return $this->nrOfInstallments;
    }
}
