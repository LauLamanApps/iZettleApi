<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\AbstractPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\CardPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\CashPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\InvoicePayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\MobilePayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\SwishPayment;
use LauLamanApps\IzettleApi\API\Purchase\Payment\VippsPayment;
use LauLamanApps\IzettleApi\Client\Purchase\Exception\PaymentTypeNotConfiguredException;
use LauLamanApps\IzettleApi\Client\Purchase\PaymentBuilder;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class PaymentBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function parseArray(): void
    {
        $paymentData = [
            [
                "uuid" => (string) Uuid::uuid1(),
                "amount" => 100,
                "type" => PaymentBuilder::CASH,
                "attributes" => [
                    "handedAmount" => 500,
                ],
            ],
            [
                "uuid" => (string) Uuid::uuid1(),
                "amount" => 200,
                "type" => PaymentBuilder::CASH,
                "attributes" => [
                    "handedAmount" => 500,
                ],
            ],
        ];

        $builder = new PaymentBuilder();
        $payments = $builder->buildFromArray($paymentData, new Currency('EUR'));

        foreach ($payments as $payment) {
            $this->assertInstanceOf(AbstractPayment::class, $payment);
        }
    }

    /**
     * @test
     * @dataProvider getPaymentData
     */
    public function build($paymentData, $expectedClass): void
    {
        $builder = new PaymentBuilder();
        $payment = $builder->build($paymentData, new Currency('EUR'));

        $this->assertInstanceOf(AbstractPayment::class, $payment);
        $this->assertInstanceOf($expectedClass, $payment);
        $this->assertSame($paymentData['uuid'], (string) $payment->getUuid());
        $this->assertSame($paymentData['amount'], (int) $payment->getAmount()->getAmount());

        if ($payment instanceof CashPayment) {
            $this->cashPaymentTests($payment, $paymentData);
        }
        if ($payment instanceof CardPayment) {
            $this->cardPaymentTests($payment, $paymentData);
        }
        if ($payment instanceof InvoicePayment) {
            $this->invoicePaymentTests($payment, $paymentData);
        }
    }

    private function cashPaymentTests(CashPayment $payment, $paymentData): void
    {
        $this->assertSame($paymentData['attributes']['handedAmount'], (int) $payment->getHandedAmount()->getAmount());
    }

    private function cardPaymentTests(CardPayment $payment, $paymentData): void
    {
        $this->assertSame($paymentData['attributes']['cardPaymentEntryMode'], $payment->getCardPaymentEntryMode());
        $this->assertSame($paymentData['attributes']['maskedPan'], $payment->getMaskedPan());
        $this->assertSame($paymentData['attributes']['referenceNumber'], $payment->getReferenceNumber());
        $this->assertSame($paymentData['attributes']['nrOfInstallments'], $payment->getNrOfInstallments());
        $this->assertSame($paymentData['attributes']['cardType'], $payment->getCardType());
        $this->assertSame($paymentData['attributes']['terminalVerificationResults'], $payment->getTerminalVerificationResults());
        if (array_key_exists('applicationIdentifier', $paymentData['attributes'])) {
            $this->assertSame($paymentData['attributes']['applicationIdentifier'], $payment->getApplicationIdentifier());
        }
        if (array_key_exists('applicationName', $paymentData['attributes'])) {
            $this->assertSame($paymentData['attributes']['applicationName'], $payment->getApplicationName());
        }
    }

    private function invoicePaymentTests(InvoicePayment $payment, $paymentData): void
    {
        $this->assertSame($paymentData['attributes']['orderUUID'], (string) $payment->getOrderUuid());
        $this->assertSame($paymentData['attributes']['invoiceNr'], $payment->getInvoiceNr());
        $this->assertSame($paymentData['attributes']['dueDate'], $payment->getDueDate()->format('Y-m-d'));
    }

    public function getPaymentData(): array
    {
        return [
            PaymentBuilder::CARD . 1 => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 100,
                    "type" => PaymentBuilder::CARD,
                    "attributes" => [
                        "cardPaymentEntryMode" => "CONTACTLESS_EMV",
                        "maskedPan" => "123456*********1234",
                        "referenceNumber" => "XH3WXTAUFB",
                        "nrOfInstallments" => 0,
                        "cardType" => "MAESTRO",
                        "terminalVerificationResults" => "0000001234",
                        "applicationIdentifier" => "A0000000012345",
                        "applicationName" => "MAESTRO",
                    ],
                ],
                CardPayment::class,
            ],
            PaymentBuilder::CARD . 2 => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 100,
                    "type" => PaymentBuilder::CARD,
                    "attributes" => [
                        "cardPaymentEntryMode" => "CONTACTLESS_EMV",
                        "maskedPan" => "123456*********1234",
                        "referenceNumber" => "XH3WXTAUFB",
                        "nrOfInstallments" => 0,
                        "cardType" => "MAESTRO",
                        "terminalVerificationResults" => "0000001234",
                    ],
                ],
                CardPayment::class,
            ],
            PaymentBuilder::CASH => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 200,
                    "type" => PaymentBuilder::CASH,
                    "attributes" => [
                        "handedAmount" => 500,
                    ],
                ],
                CashPayment::class,
            ],
            PaymentBuilder::INVOICE => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 300,
                    "type" => PaymentBuilder::INVOICE,
                    "attributes" => [
                        "orderUUID" => "d5b126c4-979e-11e7-9af0-a3d2806c42a1",
                        "invoiceNr" => "iz37",
                        "dueDate" => "2017-10-12",
                    ],
                ],
                InvoicePayment::class,
            ],
            PaymentBuilder::MOBILE => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 400,
                    "type" => PaymentBuilder::MOBILE,
                ],
                MobilePayment::class,
            ],
            PaymentBuilder::SWISH => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 500,
                    "type" => PaymentBuilder::SWISH,
                ],
                SwishPayment::class,
            ],
            PaymentBuilder::VIPPS => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 600,
                    "type" => PaymentBuilder::VIPPS,
                ],
                VippsPayment::class,
            ],
        ];
    }

    /**
     * @test
     */
    public function parseNonConfiguredPaymentType(): void
    {
        $builder = new PaymentBuilder();

        $this->expectException(PaymentTypeNotConfiguredException::class);

        $builder->build(['type' => 'IZETTLE_NON_EXISTENCE'], new Currency('EUR'));
    }
}
