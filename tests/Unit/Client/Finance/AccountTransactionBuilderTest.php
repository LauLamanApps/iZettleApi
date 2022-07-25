<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Finance;

use LauLamanApps\IzettleApi\Client\Finance\AccountTransactionBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class AccountTransactionBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function buildFromJson(): void
    {
        $json = file_get_contents(__DIR__ . '/json-files/account-transaction.json');
        $data = json_decode($json, true)['data'];

        $accountTransactionBuilder = new AccountTransactionBuilder();
        $accountTransactions = $accountTransactionBuilder->buildFromJson($json);

        foreach ($accountTransactions as $index => $accountTransaction) {
            self::assertSame($data[$index]['originatingTransactionUuid'], (string) $accountTransaction->getOriginatingTransactionUuid());
            self::assertSame($data[$index]['originatorTransactionType'], $accountTransaction->getOriginatorTransactionType()->value);
            self::assertSame($data[$index]['timestamp'], $accountTransaction->getTimestamp()->format('Y-m-d H:i:s'));
            self::assertSame($data[$index]['amount'], $accountTransaction->getAmount());
        }
    }
}
