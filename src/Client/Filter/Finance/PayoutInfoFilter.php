<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Filter\Finance;

use DateTime;
use LauLamanApps\IzettleApi\Client\Filter\FilterInterface;

final class PayoutInfoFilter implements FilterInterface
{
    /**
     * @var DateTime
     */
    private $at;

    public function __construct(DateTime $at)
    {
        $this->at = $at;
    }

    public function getParameters(): array
    {
        return [
            'at' => $this->at->format('Y-m-d')
        ];
    }
}
