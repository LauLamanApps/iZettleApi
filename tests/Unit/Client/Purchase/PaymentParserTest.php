<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Client\Purchase;

use LauLamanApps\iZettleApi\API\Purchase\AbstractPayment;
use LauLamanApps\iZettleApi\API\Purchase\Payment\CardPayment;
use LauLamanApps\iZettleApi\API\Purchase\Payment\CashPayment;
use LauLamanApps\iZettleApi\Client\Purchase\PaymentParser;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class PaymentParserTest extends TestCase
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
                "type" => PaymentParser::CASH,
                "attributes" => [
                    "handedAmount" => 500,
                ]
            ],
            [
                "uuid" => (string) Uuid::uuid1(),
                "amount" => 200,
                "type" => PaymentParser::CASH,
                "attributes" => [
                    "handedAmount" => 500,
                ]
            ],
        ];
        $payments = PaymentParser::parseArray($paymentData, new Currency('EUR'));

        foreach ($payments as $payment) {
            self::assertInstanceOf(AbstractPayment::class, $payment);
        }
    }

    /**
     * @test
     * @dataProvider getPaymentData
     */
    public function parse($paymentData, $expectedClass): void
    {
        $payment = PaymentParser::parse($paymentData, new Currency('EUR'));

        self::assertInstanceOf(AbstractPayment::class, $payment);
        self::assertInstanceOf($expectedClass, $payment);
        self::assertSame($paymentData['uuid'], (string) $payment->getUuid());
        self::assertSame($paymentData['amount'], (int) $payment->getAmount()->getAmount());

        if ($payment instanceof CashPayment) {
            $this->cashPaymentTests($payment, $paymentData);
        }
        if ($payment instanceof CardPayment) {
            $this->cardPaymentTests($payment, $paymentData);
        }
    }

    private function cashPaymentTests(CashPayment $payment, $paymentData)
    {
        self::assertSame($paymentData['attributes']['handedAmount'], (int) $payment->getHandedAmount()->getAmount());
    }

    private function cardPaymentTests(CardPayment $payment, $paymentData)
    {
        self::assertSame($paymentData['attributes']['cardPaymentEntryMode'], $payment->getCardPaymentEntryMode());
        self::assertSame($paymentData['attributes']['maskedPan'], $payment->getMaskedPan());
        self::assertSame($paymentData['attributes']['referenceNumber'], $payment->getReferenceNumber());
        self::assertSame($paymentData['attributes']['nrOfInstallments'], $payment->getNrOfInstallments());
        self::assertSame($paymentData['attributes']['cardType'], $payment->getCardType());
        self::assertSame($paymentData['attributes']['terminalVerificationResults'], $payment->getTerminalVerificationResults());
        self::assertSame($paymentData['attributes']['applicationIdentifier'], $payment->getApplicationIdentifier());
        self::assertSame($paymentData['attributes']['applicationName'], $payment->getApplicationName());
    }

    public function getPaymentData(): array
    {
        return [
            PaymentParser::CARD => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 100,
                    "type" => PaymentParser::CARD,
                    "attributes" => [
                        "cardPaymentEntryMode" => "CONTACTLESS_EMV",
                        "maskedPan" => "123456*********1234",
                        "referenceNumber" => "XH3WXTAUFB",
                        "nrOfInstallments" => 0,
                        "cardType" => "MAESTRO",
                        "terminalVerificationResults" => "0000001234",
                        "applicationIdentifier" => "A0000000012345",
                        "applicationName" => "MAESTRO",
                    ]
                ],
                CardPayment::class
            ],
            PaymentParser::CASH => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 200,
                    "type" => PaymentParser::CASH,
                    "attributes" => [
                        "handedAmount" => 500,
                    ]
                ],
                CashPayment::class
            ],
        ];
    }

    /**
     * @test
     * @expectedException \LauLamanApps\iZettleApi\Client\Exceptions\PaymentTypeNotConfiguredException
     */
    public function parseNonConfiguredPaymentType(): void
    {
        $payment = PaymentParser::parse(['type' => 'IZETTLE_NON_EXISTENCE'], new Currency('EUR'));
    }

    /**
     * @test
     * @dataProvider getNonImplementedPaymentData
     * @expectedException \Exception
     * @expectedExceptionMessage('Payment type not implemented')
     *
     * We have a test for the non implemented payment types so when someone opens
     * a PR to Add a payment type this test fails and we can write a correct test.
     */
    public function parseNonImplemented($paymentData): void
    {
        $payment = PaymentParser::parse($paymentData, new Currency('EUR'));
    }

    public function getNonImplementedPaymentData(): array
    {
        return [
            PaymentParser::INVOICE => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 300,
                    "type" => PaymentParser::INVOICE,
                    "attributes" => [
                        "unknownFields" => true
                    ]
                ]
            ],
            PaymentParser::MOBILE => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 400,
                    "type" => PaymentParser::MOBILE,
                    "attributes" => [
                        "unknownFields" => true
                    ]
                ]
            ],
            PaymentParser::SWISH => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 500,
                    "type" => PaymentParser::SWISH,
                    "attributes" => [
                        "unknownFields" => true
                    ]
                ],
            ],
            PaymentParser::VIPPS => [
                [
                    "uuid" => (string) Uuid::uuid1(),
                    "amount" => 600,
                    "type" => PaymentParser::VIPPS,
                    "attributes" => [
                        "unknownFields" => true
                    ]
                ],
            ],
        ];
    }
}
