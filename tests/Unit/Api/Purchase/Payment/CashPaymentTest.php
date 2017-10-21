<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Api\Purchase\Payment;

use LauLamanApps\iZettleApi\API\Purchase\Payment\CashPayment;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class CashPaymentTest extends TestCase
{
    /**
     * @test
     * @dataProvider getAmounts
     */
    public function changedAmount($shouldPay, $payed)
    {
        $shouldReceiveChange = ($payed - $shouldPay);

        $payment = new CashPayment(Uuid::uuid1(), Money::EUR($shouldPay), Money::EUR($payed));

        self::assertSame($shouldReceiveChange, (int) $payment->getChangeAmount()->getAmount());
    }

    public function getAmounts(): array
    {
        return [
            'positive 1' => [200, 500],
            'positive 2' => [1000, 250],
            'negative 1' => [-100, 0],
            'negative 2' => [-1000, -1000],
        ];
    }
}
