<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Filter\Finance;

use DateTime;
use LauLamanApps\IzettleApi\Client\Filter\FilterInterface;

final class AccountTransactionsFilter implements FilterInterface
{
    /**
     * @var DateTime
     */
    private $start;

    /**
     * @var DateTime
     */
    private $end;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * @var int|null
     */
    private $offset;


    public function __construct(
        DateTime $start,
        DateTime $end,
        ?int $limit = null,
        ?int $offset = null
    ) {
        $this->start = $start;
        $this->end = $end;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getParameters(): array
    {
        return [
            'start' => $this->start->format('Y-m-d'),
            'end' => $this->end->format('Y-m-d'),
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }
}
