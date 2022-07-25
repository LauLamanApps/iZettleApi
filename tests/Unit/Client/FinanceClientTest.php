<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client;

use DateTime;
use LauLamanApps\IzettleApi\API\Finance\Enum\AccountTypeGroup;
use LauLamanApps\IzettleApi\API\Finance\Enum\Periodicity;
use LauLamanApps\IzettleApi\API\Finance\PayoutInfo;
use LauLamanApps\IzettleApi\Client\Finance\AccountTransactionBuilderInterface;
use LauLamanApps\IzettleApi\Client\Finance\PayoutInfoBuilderInterface;
use LauLamanApps\IzettleApi\Client\FinanceClient;
use Mockery;
use Mockery\MockInterface;
use Money\Money;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class FinanceClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getAccountTransactions(): void
    {
        $accountTypeGroup =  AccountTypeGroup::LIQUID;
        $organizationUuid = Uuid::uuid1();
        $start = new DateTime('now');
        $end = new DateTime("+10 seconds");

        $url = sprintf(FinanceClient::GET_ACCOUNT_TRANSACTIONS, (string) $organizationUuid, $accountTypeGroup->value);
        $queryParams = [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'limit' => null,
            'offset' => null,
        ];
        $data = ['getAccountTransactionsTest'];

        $izettleClientMock = $this->getIzettleGetMock($url, $data, $queryParams);

        [$accountTransactionBuilderMock, $payoutInfoBuilderMock] = $this->getDependencyMocks();
        $accountTransactionBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once()->andReturn([]);

        $financeClient = new FinanceClient($izettleClientMock, $organizationUuid, $accountTransactionBuilderMock, $payoutInfoBuilderMock);
        $financeClient->getAccountTransactions($accountTypeGroup, $start, $end);
    }

    /**
     * @test
     */
    public function getBalanceInfo(): void
    {
        $accountTypeGroup =  AccountTypeGroup::LIQUID;
        $organizationUuid = Uuid::uuid1();

        $expectedBalance = Money::EUR(100);

        $url = sprintf(FinanceClient::GET_ACCOUNT_BALANCE, (string) $organizationUuid, $accountTypeGroup->value);
        $queryParams = ['at' => null];
        $data = [
            'data' => [
                'currencyId' => $expectedBalance->getCurrency()->getCode(),
                'totalBalance' => (int) $expectedBalance->getAmount(),
            ],
        ];

        $izettleClientMock = $this->getIzettleGetMock($url, $data, $queryParams);

        [$accountTransactionBuilderMock, $payoutInfoBuilderMock] = $this->getDependencyMocks();

        $financeClient = new FinanceClient($izettleClientMock, $organizationUuid, $accountTransactionBuilderMock, $payoutInfoBuilderMock);
        $balance = $financeClient->getBalanceInfo($accountTypeGroup);

        self::assertEquals($expectedBalance, $balance);
    }

    /**
     * @test
     */
    public function getPayoutInfo(): void
    {
        $organizationUuid = Uuid::uuid1();

        $url = sprintf(FinanceClient::GET_PAYOUT_INFO, (string) $organizationUuid);
        $queryParams = ['at' => null];
        $data = ['getPayoutInfoTest'];

        $izettleClientMock = $this->getIzettleGetMock($url, $data, $queryParams);

        [$accountTransactionBuilderMock, $payoutInfoBuilderMock] = $this->getDependencyMocks();
        $payoutInfoBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once()->andReturn($this->getPayoutInfoObject());

        $financeClient = new FinanceClient($izettleClientMock, $organizationUuid, $accountTransactionBuilderMock, $payoutInfoBuilderMock);
        $financeClient->getPayoutInfo();
    }

    /**
     * @return MockInterface[]
     */
    protected function getDependencyMocks(): array
    {
        return [
            Mockery::mock(AccountTransactionBuilderInterface::class),
            Mockery::mock(PayoutInfoBuilderInterface::class),
        ];
    }

    private function getPayoutInfoObject(): PayoutInfo
    {
        return new PayoutInfo(
            Money::EUR(0),
            Money::EUR(1),
            Money::EUR(2),
            Periodicity::DAILY
        );
    }
}
