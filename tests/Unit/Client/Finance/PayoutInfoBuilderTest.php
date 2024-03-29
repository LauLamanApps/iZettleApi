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
        $json = file_get_contents(__DIR__ . '/json-files/payout-info.json');
        $data = json_decode($json, true)['data'];

        $payoutInfoBuilder = new PayoutInfoBuilder();
        $payoutInfo = $payoutInfoBuilder->buildFromJson($json);

        $this->assertSame($data['totalBalance'], (int) $payoutInfo->getTotalBalance()->getAmount());
        $this->assertSame($data['currencyId'], $payoutInfo->getTotalBalance()->getCurrency()->getCode());
        $this->assertSame($data['nextPayoutAmount'], (int) $payoutInfo->getNextPayoutAmount()->getAmount());
        $this->assertSame($data['currencyId'], $payoutInfo->getNextPayoutAmount()->getCurrency()->getCode());
        $this->assertSame($data['discountRemaining'], (int) $payoutInfo->getDiscountRemaining()->getAmount());
        $this->assertSame($data['currencyId'], $payoutInfo->getDiscountRemaining()->getCurrency()->getCode());
        $this->assertSame($data['periodicity'], $payoutInfo->getPeriodicity()->value);
    }
}
