<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\Coordinates;
use LauLamanApps\IzettleApi\API\Purchase\Exception\InvalidLatitudeException;
use LauLamanApps\IzettleApi\API\Purchase\Exception\InvalidLongitudeException;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class CoordinatesTest extends TestCase
{
    /**
     * @test
     * @dataProvider getCoordinates
     */
    public function coordinate(float $latitude, float $longitude, float $accuracyMeters): void
    {
        $coordinate = new Coordinates($latitude, $longitude, $accuracyMeters);

        $this->assertSame($latitude, $coordinate->getLatitude());
        $this->assertSame($longitude, $coordinate->getLongitude());
        $this->assertSame($accuracyMeters, $coordinate->getAccuracyMeters());
    }

    public function getCoordinates(): array
    {
        return [
            'Almere Poort, The Netherlands' => [52.3504547, 5.1511458, 10],
            'Amsterdam, The Netherlands' => [52.3702157, 4.8951679, 0],
            'Den Haag, The Netherlands' => [52.0704978, 4.3006999, -10.1],
        ];
    }

    /**
     * @test
     * @dataProvider getInvalidLatitude
     */
    public function invalidLatitude(float $latitude): void
    {
        $this->expectException(InvalidLatitudeException::class);

        new Coordinates($latitude, 5.1511458, 10.0);
    }

    public function getInvalidLatitude(): array
    {
        return [
            [-90.0000001],
            [90.0000001],
        ];
    }

    /**
     * @test
     * @dataProvider getInvalidLongitude
     */
    public function invalidLongitude(float $longitude): void
    {
        $this->expectException(InvalidLongitudeException::class);

        new Coordinates(52.3504547, $longitude, 10.0);
    }

    public function getInvalidLongitude(): array
    {
        return [
            [-180.0000001],
            [180.0000001],
        ];
    }
}
