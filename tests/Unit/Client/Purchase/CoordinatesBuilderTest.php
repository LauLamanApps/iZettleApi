<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\Coordinates;
use LauLamanApps\IzettleApi\Client\Purchase\CoordinatesBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class CoordinatesBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider getCoordinates
     */
    public function parse(float $latitude, float $longitude, float $accuracyMeters): void
    {
        $data = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'accuracyMeters' => $accuracyMeters,
        ];

        $builder = new CoordinatesBuilder();
        $coordinate = $builder->buildFromArray($data);

        $this->assertInstanceOf(Coordinates::class, $coordinate);
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
}
