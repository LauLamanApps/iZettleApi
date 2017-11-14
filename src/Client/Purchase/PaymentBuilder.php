<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use DateTime;
use LauLamanApps\IzettleApi\API\Purchase\AbstractPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\CardPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\CashPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\InvoicePayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\MobilePayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\SwishPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\VippsPayment;
use LauLamanApps\IzettleApi\Client\Exception\PaymentTypeNotConfiguredException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\Uuid;

final class PaymentBuilder implements PaymentBuilderInterface
{
    const CARD = 'IZETTLE_CARD';
    const CASH = 'IZETTLE_CASH';
    const INVOICE = 'IZETTLE_INVOICE';
    const MOBILE = 'MOBILE_PAY';
    const SWISH = 'SWISH';
    const VIPPS = 'VIPPS';

    public function buildFromArray(array $payments, Currency $currency): array
    {
        $data = [];
        foreach ($payments as $payment) {
            $data[] = $this->build($payment, $currency);
        }

        return $data;
    }

    public function build(array $payment, Currency $currency): AbstractPayment
    {
        switch ($payment['type']) {
            case self::CARD:
                return $this->parseCardPayment($payment, $currency);
            case self::CASH:
                return $this->parseCashPayment($payment, $currency);
            case self::INVOICE:
                return $this->parseInvoicePayment($payment, $currency);
            case self::MOBILE:
                return $this->parseMobilePayment($payment, $currency);
            case self::SWISH:
                return $this->parseSwichPayment($payment, $currency);
            case self::VIPPS:
                return $this->parseVippsPayment($payment, $currency);
        }

        throw new PaymentTypeNotConfiguredException('Payment type \'' . $payment['type'] . '\' not configured');
    }

    private function parseCardPayment($payment, Currency $currency): CardPayment
    {
        return new CardPayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency),
            $payment['attributes']['referenceNumber'],
            $payment['attributes']['maskedPan'],
            $payment['attributes']['cardType'],
            $payment['attributes']['cardPaymentEntryMode'],
            $this->getFromKey('applicationName', $payment['attributes']),
            $this->getFromKey('applicationIdentifier', $payment['attributes']),
            $this->getFromKey('terminalVerificationResults', $payment['attributes']),
            (int) $this->getFromKey('nrOfInstallments', $payment['attributes'])
        );
    }

    private function parseCashPayment($payment, Currency $currency): CashPayment
    {
        return new CashPayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency),
            new Money($payment['attributes']['handedAmount'], $currency)
        );
    }

    private function parseInvoicePayment($payment, Currency $currency): InvoicePayment
    {
        return new InvoicePayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency),
            Uuid::fromString($payment['attributes']['orderUUID']),
            $payment['attributes']['invoiceNr'],
            new DateTime($payment['attributes']['dueDate'])
        );
    }

    private function parseMobilePayment($payment, Currency $currency): MobilePayment
    {
        return new MobilePayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency)
        );
    }

    private function parseSwichPayment($payment, Currency $currency): SwishPayment
    {
        return new SwishPayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency)
        );
    }

    private function parseVippsPayment($payment, Currency $currency): VippsPayment
    {
        return new VippsPayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency)
        );
    }

    private function getFromKey($key, array $data)
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        return $data[$key];
    }
}
