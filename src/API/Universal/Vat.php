<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Universal;

use InvalidArgumentException;

final class Vat
{
    private $percentage;

    public function __construct(string $percentage)
    {
        $this->validate($percentage);
        $this->percentage = $percentage;
    }

    public function getPercentage(): string
    {
        return $this->percentage;
    }

    private function validate(string $percentage): void
    {
        if (!is_numeric($percentage)) {
            throw new InvalidArgumentException('string is not a valid number');
        }

        if (ceil((float) $percentage) >= 100 && floor((float) $percentage) >= 100) {
            throw new InvalidArgumentException('Value to high, must be at most 99.999.');
        }

        if (floor((float) $percentage) <= 0 && ceil((float) $percentage) != 1) {
            throw new InvalidArgumentException('Value to low, must be more than 0.');
        }
    }
}
