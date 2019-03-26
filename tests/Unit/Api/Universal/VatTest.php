<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Universal;

use LauLamanApps\IzettleApi\API\Universal\Vat;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class VatTest extends TestCase
{
    /**
     * @test
     * @dataProvider getValidValues
     */
    public function canCreateVatWithValidValue(string $value): void
    {
        $vat = new Vat($value);

        self::assertSame($value, $vat->getPercentage());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage string is not a valid number
     * @dataProvider getToInvalidValues
     */
    public function cantCreateVatWithInvalidValue(string $value): void
    {
        new Vat($value);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Value to high, must be at most 99.999.
     * @dataProvider getToHighValues
     */
    public function cantCreateVatWithToHighValue(string $value): void
    {
        new Vat($value);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Value to low, must be more than 0.
     * @dataProvider getToLowValues
     */
    public function cantCreateVatWithToLowValue(string $value): void
    {
        new Vat($value);
    }

    public function getValidValues(): array
    {
        /*
         * Here we test all the current EU VAT rates.
         * Please feel free to aad the rates for your country
         */
        return [
            'Minimum' => ['0.01'],
            'Austria 20% Standard' => ['20'],
            'Austria 13% Reduced' => ['13'],
            'Austria 10% Reduced' => ['10'],
            'Belgium 21% Standard' => ['21'],
            'Belgium 12% Reduced' => ['12'],
            'Belgium 6% Reduced' => ['6'],
            'Bulgaria 20% Standard' => ['20'],
            'Bulgaria 9% Reduced' => ['9'],
            'Croatia 25% Standard' => ['25'],
            'Croatia 13% Reduced' => ['13'],
            'Croatia 5% Reduced' => ['5'],
            'Cyprus 19% Standard' => ['19'],
            'Cyprus 9% Reduced' => ['9'],
            'Cyprus 5% Reduced' => ['5'],
            'Czech Republic 21% Standard' => ['21'],
            'Czech Republic 15% Reduced' => ['15'],
            'Czech Republic10% Reduced' => ['10'],
            'Denmark 25% Standard' => ['25'],
            'Estonia 20% Standard' => ['20'],
            'Estonia 9% Reduced' => ['9'],
            'Finland 24% Standard' => ['24'],
            'Finland 14% Reduced' => ['14'],
            'Finland 10% Reduced' => ['10'],
            'France 20% Standard' => ['20'],
            'France 10% Reduced' => ['10'],
            'France 5.5% Reduced' => ['5.5'],
            'France 2.1% Reduced' => ['2.1'],
            'Germany 19% Standard' => ['19'],
            'Germany 7% Reduced' => ['7'],
            'Greece 24% Standard' => ['24'],
            'Greece 13% Reduced' => ['13'],
            'Greece 6% Reduced' => ['6'],
            'Hungary 27% Standard' => ['27'],
            'Hungary 18% Reduced' => ['18'],
            'Hungary 5% Reduced' => ['5'],
            'Ireland 23% Standard' => ['23'],
            'Ireland 13.5% Reduced' => ['13.5'],
            'Ireland 9% Reduced' => ['9'],
            'Ireland 4.8% Reduced' => ['4.8'],
            'Italy 22% Standard' => ['22'],
            'Italy 10% Reduced' => ['10'],
            'Italy 5% Reduced' => ['5'],
            'Italy 4% Reduced' => ['4'],
            'Latvia 21% Standard' => ['21'],
            'Latvia 12% Reduced' => ['12'],
            'Latvia 5% Reduced' => ['5'],
            'Lithuania 21% Standard' => ['21'],
            'Lithuania 9% Reduced' => ['9'],
            'Lithuania 5% Reduced' => ['5'],
            'Luxembourg 17% Standard' => ['17'],
            'Luxembourg 14% Reduced' => ['14'],
            'Luxembourg 8% Reduced' => ['8'],
            'Luxembourg 3% Reduced' => ['3'],
            'Malta 18% Standard' => ['18'],
            'Malta 7% Reduced' => ['7'],
            'Malta 5% Reduced' => ['5'],
            'Netherlands 21% Standard' => ['21'],
            'Netherlands 9% Reduced' => ['9'],
            'Poland 23% Standard' => ['23'],
            'Poland 8% Reduced' => ['8'],
            'Poland 5% Reduced' => ['5'],
            'Portugal 23% Standard' => ['23'],
            'Portugal 13% Reduced' => ['13'],
            'Portugal 6% Reduced' => ['6'],
            'Romania 19% Standard' => ['19'],
            'Romania 9% Reduced' => ['9'],
            'Romania 5% Reduced' => ['5'],
            'Slovakia 20% Standard' => ['20'],
            'Slovakia 10% Reduced' => ['10'],
            'Slovenia 22% Standard' => ['22'],
            'Slovenia 9.5% Reduced' => ['9.5'],
            'Spain 21% Standard' => ['21'],
            'Spain 10% Reduced' => ['10'],
            'Spain 4% Reduced' => ['4'],
            'Sweden 25% Standard' => ['25'],
            'Sweden 12% Reduced' => ['12'],
            'Sweden 6% Reduced' => ['6'],
            'United Kingdom 20% Standard' => ['20'],
            'United Kingdom 5% Reduced' => ['5'],
            'Maximum' => ['99.999'],
        ];
    }

    public function getToInvalidValues(): array
    {
        return [
            'string' => ['Lorem ipsum dolor sit amet'],
            'number with comma' => ['10,1'],
            'number with multiple dots' => ['10.10.10'],
        ];
    }

    public function getToHighValues(): array
    {
        return [
            '100.00' => ['100.00'],
            '100.01' => ['100.01'],
            '100.5' => ['100.5'],
            '100.9' => ['100.9'],
        ];
    }

    public function getToLowValues(): array
    {
        return [
            '0.00' => ['0.00'],
            '-0.01' => ['-0.01'],
        ];
    }
}
