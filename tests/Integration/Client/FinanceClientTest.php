<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Integration\Client;

use DateTime;
use LauLamanApps\IzettleApi\API\Finance\Enum\AccountTypeGroup;
use LauLamanApps\IzettleApi\Client\Filter\Finance\AccountTransactionsFilter;
use LauLamanApps\IzettleApi\Client\Filter\Finance\BalanceInfoFilter;
use LauLamanApps\IzettleApi\Client\Filter\Finance\PayoutInfoFilter;
use LauLamanApps\IzettleApi\IzettleClientFactory;

/**
 * @medium
 */
final class FinanceClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getAccountTransactions(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/files/FinanceClient/getAccountTransactions.json');
        $data = json_decode($json, true)['data'];

        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getFinanceClient($iZettleClient);

        $accountTransactions = $purchaseClient->getAccountTransactions(
            AccountTypeGroup::get(AccountTypeGroup::LIQUID),
            new AccountTransactionsFilter(new DateTime(), new DateTime())
        );

        foreach ($accountTransactions as $index => $accountTransaction) {
            self::assertSame($data[$index]['originatingTransactionUuid'], (string) $accountTransaction->getOriginatingTransactionUuid());
            self::assertSame($data[$index]['originatorTransactionType'], $accountTransaction->getOriginatorTransactionType()->getValue());
            self::assertEquals(new DateTime($data[$index]['timestamp']), $accountTransaction->getTimestamp());
            self::assertSame($data[$index]['amount'], $accountTransaction->getAmount());
        }
    }

    /**
     * @test
     */
    public function getBalanceInfo(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/files/FinanceClient/getBalanceInfo.json');
        $data = json_decode($json, true)['data'];

        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getFinanceClient($iZettleClient);

        $balance = $purchaseClient->getBalanceInfo(new BalanceInfoFilter(AccountTypeGroup::liquid(), new DateTime()));

        self::assertSame($data['currencyId'], $balance->getCurrency()->getCode());
        self::assertSame($data['totalBalance'], (int) $balance->getAmount());
    }

    /**
     * @test
     */
    public function getPayoutInfo(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/files/FinanceClient/getPayoutInfo.json');
        $data = json_decode($json, true)['data'];

        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getFinanceClient($iZettleClient);

        $payoutInfo = $purchaseClient->getPayoutInfo(new PayoutInfoFilter(new DateTime()));

        self::assertSame($data['totalBalance'], (int) $payoutInfo->getTotalBalance()->getAmount());
        self::assertSame($data['currencyId'], $payoutInfo->getTotalBalance()->getCurrency()->getCode());
        self::assertSame($data['nextPayoutAmount'], (int) $payoutInfo->getNextPayoutAmount()->getAmount());
        self::assertSame($data['currencyId'], $payoutInfo->getNextPayoutAmount()->getCurrency()->getCode());
        self::assertSame($data['discountRemaining'], (int) $payoutInfo->getDiscountRemaining()->getAmount());
        self::assertSame($data['currencyId'], $payoutInfo->getDiscountRemaining()->getCurrency()->getCode());
        self::assertSame($data['periodicity'], $payoutInfo->getPeriodicity()->getValue());
    }
}
