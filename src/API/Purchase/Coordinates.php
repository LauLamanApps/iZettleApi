<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Purchase;

use LauLamanApps\iZettleApi\API\Purchase\Exceptions\InvalidLatitudeException;
use LauLamanApps\iZettleApi\API\Purchase\Exceptions\InvalidLongitudeException;

final class Coordinates
{
    private $latitude;
    private $longitude;
    private $accuracyMeters;

    public function __construct(float $latitude, float $longitude, float $accuracyMeters)
    {
        $this->validateLatitude($latitude);
        $this->validateLongitude($longitude);
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->accuracyMeters = $accuracyMeters;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getAccuracyMeters(): float
    {
        return $this->accuracyMeters;
    }

    private function validateLatitude(float $latitude): void
    {
        if (!$this->isNumericInBounds($latitude, -90.0, 90.0)) {
            throw new InvalidLatitudeException(sprintf('%d is not a valid latitude', $latitude));
        }
    }

    private function validateLongitude(float $longitude): void
    {
        if (!$this->isNumericInBounds($longitude, -180.0, 180.0)) {
            throw new InvalidLongitudeException(sprintf('%d is not a valid longitude', $longitude));
        }
    }

    private function isNumericInBounds(float $value, float $min, float $max): bool
    {
        if ($value < $min || $value > $max) {
            return false;
        }

        return true;
    }
}
