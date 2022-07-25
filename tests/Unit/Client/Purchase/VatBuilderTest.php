<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Purchase;

use LauLamanApps\IzettleApi\Client\Purchase\VatBuilder;
use Money\Currency;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class VatBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function parseArray(): void
    {
        $data = [
            6 => 1000,
            21 => 500,
        ];
        $builder = new VatBuilder();
        $vatAmount = $builder->buildFromArray($data, new Currency('EUR'));

        foreach ($vatAmount as $vat => $amount) {
            $this->assertTrue(array_key_exists($vat, $data));
            $this->assertSame($data[$vat], (int) $amount->getAmount());
        }
    }
}
