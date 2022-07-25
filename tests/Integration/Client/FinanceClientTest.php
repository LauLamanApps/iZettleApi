<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Integration\Client;

use DateTime;
use LauLamanApps\IzettleApi\API\Finance\Enum\AccountTypeGroup;
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
        $json = file_get_contents(__DIR__ . '/files/FinanceClient/getAccountTransactions.json');
        $data = json_decode($json, true)['data'];

        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getFinanceClient($iZettleClient);

        $accountTransactions = $purchaseClient->getAccountTransactions(
            AccountTypeGroup::LIQUID,
            new DateTime(),
            new DateTime()
        );

        foreach ($accountTransactions as $index => $accountTransaction) {
            $this->assertSame($data[$index]['originatingTransactionUuid'], (string) $accountTransaction->getOriginatingTransactionUuid());
            $this->assertSame($data[$index]['originatorTransactionType'], $accountTransaction->getOriginatorTransactionType()->value);
            $this->assertEquals(new DateTime($data[$index]['timestamp']), $accountTransaction->getTimestamp());
            $this->assertSame($data[$index]['amount'], $accountTransaction->getAmount());
        }
    }

    /**
     * @test
     */
    public function getBalanceInfo(): void
    {
        $json = file_get_contents(__DIR__ . '/files/FinanceClient/getBalanceInfo.json');
        $data = json_decode($json, true)['data'];

        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getFinanceClient($iZettleClient);

        $balance = $purchaseClient->getBalanceInfo(AccountTypeGroup::LIQUID, new DateTime());

        $this->assertSame($data['currencyId'], $balance->getCurrency()->getCode());
        $this->assertSame($data['totalBalance'], (int) $balance->getAmount());
    }

    /**
     * @test
     */
    public function getPayoutInfo(): void
    {
        $json = file_get_contents(__DIR__ . '/files/FinanceClient/getPayoutInfo.json');
        $data = json_decode($json, true)['data'];

        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getFinanceClient($iZettleClient);

        $payoutInfo = $purchaseClient->getPayoutInfo(new DateTime());

        $this->assertSame($data['totalBalance'], (int) $payoutInfo->getTotalBalance()->getAmount());
        $this->assertSame($data['currencyId'], $payoutInfo->getTotalBalance()->getCurrency()->getCode());
        $this->assertSame($data['nextPayoutAmount'], (int) $payoutInfo->getNextPayoutAmount()->getAmount());
        $this->assertSame($data['currencyId'], $payoutInfo->getNextPayoutAmount()->getCurrency()->getCode());
        $this->assertSame($data['discountRemaining'], (int) $payoutInfo->getDiscountRemaining()->getAmount());
        $this->assertSame($data['currencyId'], $payoutInfo->getDiscountRemaining()->getCurrency()->getCode());
        $this->assertSame($data['periodicity'], $payoutInfo->getPeriodicity()->value);
    }
}
