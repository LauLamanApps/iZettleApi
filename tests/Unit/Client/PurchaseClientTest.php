<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client;

use DateTime;
use LauLamanApps\IzettleApi\API\Purchase\Purchase;
use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\API\Purchase\User;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseBuilderInterface;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseHistoryBuilderInterface;
use LauLamanApps\IzettleApi\Client\PurchaseClient;
use LauLamanApps\IzettleApi\IzettleClientInterface;
use LauLamanApps\IzettleApi\Tests\Unit\MockeryAssertionTrait;
use Mockery;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class PurchaseClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getPurchaseHistory(): void
    {
        $data = ['getPurchaseHistoryTest'];
        $izettleClientMock = $this->getIzettleGetMock(PurchaseClient::GET_PURCHASES, $data);

        [$purchaseHistoryBuilder, $purchaseBuilder] = $this->getDependencyMocks();
        $purchaseHistoryBuilder->shouldReceive('buildFromJson')->with(json_encode($data))->once()->andReturn($this->getPurchaseHistoryObject());

        $purchaseClient = new PurchaseClient($izettleClientMock, $purchaseHistoryBuilder, $purchaseBuilder);
        $purchaseClient->getPurchaseHistory();
    }

    /**
     * @test
     */
    public function getPurchase(): void
    {
        $purchaseUuid = Uuid::uuid1();
        $data = ['getPurchaseTest'];
        $url = sprintf(PurchaseClient::GET_PURCHASE, (string) $purchaseUuid);
        $izettleClientMock = $this->getIzettleGetMock($url, $data);

        [$purchaseHistoryBuilder, $purchaseBuilder] = $this->getDependencyMocks();
        $purchaseBuilder->shouldReceive('buildFromJson')->with(json_encode($data))->once()->andReturn($this->getPurchaseObject());

        $purchaseClient = new PurchaseClient($izettleClientMock, $purchaseHistoryBuilder, $purchaseBuilder);
        $purchaseClient->getPurchase($purchaseUuid);
    }

    private function getPurchaseHistoryObject(): PurchaseHistory
    {
        return new PurchaseHistory('', '', []);
    }

    private function getPurchaseObject(): Purchase
    {
        return new Purchase(
            '',
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

    protected function getDependencyMocks(): array
    {
        return [
            Mockery::mock(PurchaseHistoryBuilderInterface::class),
            Mockery::mock(PurchaseBuilderInterface::class),
        ];
    }
}
