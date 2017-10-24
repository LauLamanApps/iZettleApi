<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit;

use LauLamanApps\IzettleApi\API\Purchase\Purchase;
use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\PurchaseClient;
use LauLamanApps\IzettleApi\Tests\Unit\Client\Purchase\PurchaseParserTest;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class PurchaseClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getPurchaseHistory()
    {
        $method = 'get';
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];

        $return = [
            'firstPurchaseHash' => Uuid::uuid1(),
            'lastPurchaseHash' => Uuid::uuid1(),
            'purchases' => [],
        ];

        $purchaseClient = new PurchaseClient($this->getGuzzleClient($method, PurchaseClient::GET_PURCHASES, $options, $return), $this->getAccessToken());
        $purchaseHistory = $purchaseClient->getPurchaseHistory();
        self::assertInstanceOf(PurchaseHistory::class, $purchaseHistory);
    }

    /**
     * @test
     */
    public function getPurchase()
    {
        $purchaseUuid = Uuid::uuid1();
        $method = 'get';
        $url = sprintf(PurchaseClient::GET_PURCHASE, (string) $purchaseUuid);
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', self::ACCESS_TOKEN)
            ],
            'query' => null,
        ];
        $return = (new PurchaseParserTest())->getData();

        $purchaseClient = new PurchaseClient($this->getGuzzleClient($method, $url, $options, $return), $this->getAccessToken());
        $purchase = $purchaseClient->getPurchase($purchaseUuid);
        self::assertInstanceOf(Purchase::class, $purchase);
    }
}
