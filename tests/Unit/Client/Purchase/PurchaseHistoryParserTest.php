<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseHistoryParser;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @small
 */
final class PurchaseHistoryParserTest extends TestCase
{
    /**
     * @test
     */
    public function createFromResponse(): void
    {
        $data = [
            'firstPurchaseHash' => $firstPurchaseHash = 'hash1',
            'lastPurchaseHash' => $lastPurchaseHash = 'hash2',
            'purchases' => [],
        ];

        $responseMock = Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive('getBody')->andReturnSelf();
        $responseMock->shouldReceive('getContents')->andReturn(json_encode(
            $data
        ));

        $purchaseHistory = PurchaseHistoryParser::createFromResponse($responseMock);

        self::assertInstanceOf(PurchaseHistory::class, $purchaseHistory);
        self::assertSame($firstPurchaseHash, $purchaseHistory->getFirstPurchaseHash());
        self::assertSame($lastPurchaseHash, $purchaseHistory->getLastPurchaseHash());
    }
}
