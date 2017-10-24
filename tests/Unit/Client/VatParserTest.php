<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client;

use LauLamanApps\IzettleApi\Client\Purchase\VatParser;
use Money\Currency;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class VatParserTest extends TestCase
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
        $vatAmount = VatParser::parseArray($data, new Currency('EUR'));

        foreach ($vatAmount as $vat => $amount) {
            self::assertTrue(array_key_exists($vat, $data));
            self::assertSame($data[$vat], (int)$amount->getAmount());
        }
    }
}
