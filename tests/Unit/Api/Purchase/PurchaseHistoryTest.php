<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Purchase;

use DateTime;
use LauLamanApps\IzettleApi\API\Purchase\Purchase;
use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\API\Purchase\User;
use Mockery;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

/**
 * @small
 */
final class PurchaseHistoryTest extends TestCase
{
    /**
     * @test
     */
    public function purchaseHistory()
    {
        $initialPurchases = 2;
        $purchaseHistory =  new PurchaseHistory(
            '',
            '',
            [$this->getGeneratedPurchase(), $this->getGeneratedPurchase(), new stdClass()]
        );

        self::assertEquals($initialPurchases, count($purchaseHistory->getPurchases()));

        $purchase = $this->getGeneratedPurchase();
        $purchaseHistory ->addPurchase($purchase);

        self::assertEquals(($initialPurchases +1), count($purchaseHistory->getPurchases()));

        $purchaseHistory ->removePurchase($purchase);

        self::assertEquals($initialPurchases, count($purchaseHistory->getPurchases()));
    }

    private function getGeneratedPurchase(): Purchase
    {
        return new Purchase(
            (string) Uuid::uuid1(),
        Uuid::uuid1(),
        new DateTime(),
        null,
        '',
        new User(0, ''),
        0,
        0,
        Money::EUR(0),
        Money::EUR(0),
        [],
        [],
        [],
        false,
        null,
        false,
        false
        );
    }
}
