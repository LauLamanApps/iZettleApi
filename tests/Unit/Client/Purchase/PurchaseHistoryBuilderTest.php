<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseBuilderInterface;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseHistoryBuilder;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class PurchaseHistoryBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function createFromResponse(): void
    {
        $data = [
            'firstPurchaseHash' => $firstPurchaseHash = 'hash1',
            'lastPurchaseHash' => $lastPurchaseHash = 'hash2',
            'purchases' => ['createFromResponseTest'],
        ];

        $purchaseBuilderMock = Mockery::mock(PurchaseBuilderInterface::class);
        $purchaseBuilderMock->shouldReceive('buildFromArray')->once()->with($data['purchases'])->andReturn([]);

        $builder = new PurchaseHistoryBuilder($purchaseBuilderMock);
        $purchaseHistory = $builder->buildFromJson(json_encode($data));

        self::assertInstanceOf(PurchaseHistory::class, $purchaseHistory);
        self::assertSame($firstPurchaseHash, $purchaseHistory->getFirstPurchaseHash());
        self::assertSame($lastPurchaseHash, $purchaseHistory->getLastPurchaseHash());
    }
}
