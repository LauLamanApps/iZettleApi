<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\AbstractPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\CardPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\CashPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\InvoicePayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\MobilePayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\SwishPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\VippsPayment;
use LauLamanApps\IzettleApi\Client\Exceptions\PaymentTypeNotConfiguredException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\Uuid;

final class PaymentParser
{
    const CARD = 'IZETTLE_CARD';
    const CASH = 'IZETTLE_CASH';
    const INVOICE = 'IZETTLE_INVOICE';
    const MOBILE = 'MOBILE_PAY';
    const SWISH = 'SWISH';
    const VIPPS = 'VIPPS';

    /**
     * @return AbstractPayment[]
     */
    public static function parseArray($payments, Currency $currency): array
    {
        $data = [];
        foreach ($payments as $payment) {
            $data[] = self::parse($payment, $currency);
        }

        return $data;
    }

    public static function parse(array $payment, Currency $currency): AbstractPayment
    {
        switch ($payment['type']) {
            case self::CARD:
                return self::parseCardPayment($payment, $currency);
            case self::CASH:
                return self::parseCashPayment($payment, $currency);
            case self::INVOICE:
                return self::parseInvoicePayment($payment, $currency);
            case self::MOBILE:
                return self::parseMobilePayment($payment, $currency);
            case self::SWISH:
                return self::parseSwichPayment($payment, $currency);
            case self::VIPPS:
                return self::parseVippsPayment($payment, $currency);
            default:
                throw new PaymentTypeNotConfiguredException('Payment type \'' . $payment['type'] . '\' not configured');
        }
    }

    private static function parseCardPayment($payment, Currency $currency): CardPayment
    {
        $applicationName = null;
        $applicationIdentifier = null;
        $terminalVerificationResults = null;
        $nrOfInstallments = null;

        $uuid = Uuid::fromString($payment['uuid']);
        $amount = new Money($payment['amount'], $currency);
        $referenceNumber = $payment['attributes']['referenceNumber'];
        $maskedPan = $payment['attributes']['maskedPan'];
        $cardType = $payment['attributes']['cardType'];
        $cardPaymentEntryMode = $payment['attributes']['cardPaymentEntryMode'];
        if (array_key_exists('applicationName', $payment['attributes'])) {
            $applicationName = $payment['attributes']['applicationName'];
        }
        if (array_key_exists('applicationIdentifier', $payment['attributes'])) {
            $applicationIdentifier = $payment['attributes']['applicationIdentifier'];
        }
        if (array_key_exists('terminalVerificationResults', $payment['attributes'])) {
            $terminalVerificationResults = $payment['attributes']['terminalVerificationResults'];
        }
        if (array_key_exists('nrOfInstallments', $payment['attributes'])) {
            $nrOfInstallments = (int) $payment['attributes']['nrOfInstallments'];
        }

        return new CardPayment(
            $uuid,
            $amount,
            $referenceNumber,
            $maskedPan,
            $cardType,
            $cardPaymentEntryMode,
            $applicationName,
            $applicationIdentifier,
            $terminalVerificationResults,
            $nrOfInstallments
        );
    }

    private static function parseCashPayment($payment, Currency $currency): CashPayment
    {
        return new CashPayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency),
            new Money($payment['attributes']['handedAmount'], $currency)
        );
    }

    private static function parseInvoicePayment($payment, Currency $currency): InvoicePayment
    {
        return new InvoicePayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency)
        );
    }

    private static function parseMobilePayment($payment, Currency $currency): MobilePayment
    {
        return new MobilePayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency)
        );
    }

    private static function parseSwichPayment($payment, Currency $currency): SwishPayment
    {
        return new SwishPayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency)
        );
    }

    private static function parseVippsPayment($payment, Currency $currency): VippsPayment
    {
        return new VippsPayment(
            Uuid::fromString($payment['uuid']),
            new Money($payment['amount'], $currency)
        );
    }
}
