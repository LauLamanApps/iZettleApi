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
final class PurchaseClientTest extends TestCase
{
    use MockeryAssertionTrait;

    /**
     * @test
     */
    public function getPurchaseHistory()
    {
        $data = ['getPurchaseHistoryTest'];
        $izettleClientMock = $this->getIzettleMock(PurchaseClient::GET_PURCHASES, $data);

        $purchaseHistoryBuilder = Mockery::mock(PurchaseHistoryBuilderInterface::class);
        $purchaseHistoryBuilder->shouldReceive('buildFromJson')->with(json_encode($data))->once()->andReturn($this->getPurchaseHistoryObject());
        $purchaseBuilder = Mockery::mock(PurchaseBuilderInterface::class);

        $purchaseClient = new PurchaseClient($izettleClientMock, $purchaseHistoryBuilder, $purchaseBuilder);
        $purchaseClient->getPurchaseHistory();
    }

    /**
     * @test
     */
    public function getPurchase()
    {
        $purchaseUuid = Uuid::uuid1();
        $data = ['getPurchaseTest'];
        $url = sprintf(PurchaseClient::GET_PURCHASE, (string) $purchaseUuid);
        $izettleClientMock = $this->getIzettleMock($url, $data);

        $purchaseHistoryBuilder = Mockery::mock(PurchaseHistoryBuilderInterface::class);
        $purchaseBuilder = Mockery::mock(PurchaseBuilderInterface::class);
        $purchaseBuilder->shouldReceive('buildFromJson')->with(json_encode($data))->once()->andReturn($this->getPurchaseObject());

        $purchaseClient = new PurchaseClient($izettleClientMock, $purchaseHistoryBuilder, $purchaseBuilder);
        $purchaseClient->getPurchase($purchaseUuid);
    }

    private function getIzettleMock($url, $data): IzettleClientInterface
    {
        $responseMock = Mockery::mock(ResponseInterface::class);

        $izettleClientMock = Mockery::mock(IzettleClientInterface::class);
        $izettleClientMock
            ->shouldReceive('get')
            ->once()
            ->with($url)
            ->andReturn($responseMock);
        $izettleClientMock->shouldReceive('getJson')->once()->andReturn(json_encode($data));

        return $izettleClientMock;
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
}
