<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Purchase;

use DateTime;
use LauLamanApps\IzettleApi\API\Purchase\Purchase;
use LauLamanApps\IzettleApi\API\Purchase\User;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class PurchaseTest extends TestCase
{
    /**
     * @test
     */
    public function purchase(): void
    {
        $products = ['products'];
        $payments = ['payments'];
        $vatAmounts = ['vatAmounts'];

        $purchase = new Purchase(
            '23456',
            Uuid::uuid1(),
            new DateTime(),
            null,
            'NL',
            new User(1, ''),
            1,
            2,
            Money::EUR(0),
            Money::EUR(0),
            $products,
            $payments,
            $vatAmounts,
            false,
            null,
            false,
            false
        );

        self::assertSame($products, $purchase->getProducts());
        self::assertSame($payments, $purchase->getPayments());
        self::assertSame($vatAmounts, $purchase->getVatAmounts());
    }
}
