<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Finance;

use LauLamanApps\IzettleApi\Client\Finance\PayoutInfoBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class PayoutInfoBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function buildFromJson(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/json-files/payout-info.json');
        $data = json_decode($json, true)['data'];

        $payoutInfoBuilder = new PayoutInfoBuilder();
        $payoutInfo = $payoutInfoBuilder->buildFromJson($json);

        self::assertSame($data['totalBalance'], (int) $payoutInfo->getTotalBalance()->getAmount());
        self::assertSame($data['currencyId'], $payoutInfo->getTotalBalance()->getCurrency()->getName());
        self::assertSame($data['nextPayoutAmount'], (int) $payoutInfo->getNextPayoutAmount()->getAmount());
        self::assertSame($data['currencyId'], $payoutInfo->getNextPayoutAmount()->getCurrency()->getName());
        self::assertSame($data['discountRemaining'], (int) $payoutInfo->getDiscountRemaining()->getAmount());
        self::assertSame($data['currencyId'], $payoutInfo->getDiscountRemaining()->getCurrency()->getName());
        self::assertSame($data['periodicity'], $payoutInfo->getPeriodicity()->getValue());
    }
}
