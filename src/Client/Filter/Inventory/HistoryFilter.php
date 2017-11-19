<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Filter\Inventory;

use LauLamanApps\IzettleApi\Client\Filter\FilterInterface;

final class HistoryFilter implements FilterInterface
{
    public function getParameters(): array
    {
        return ['balanceChangeType' => 'RESTOCK'];
    }
}
