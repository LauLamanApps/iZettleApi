<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Filter\Purchase;

use DateTime;
use LauLamanApps\IzettleApi\Client\Filter\FilterInterface;

final class PurchaseHistoryFilter implements FilterInterface
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @var bool
     */
    private $descending;

    /**
     * @var string|null
     */
    private $lastPurchaseHash;

    /**
     * @var DateTime|null
     */
    private $startDate;

    /**
     * @var DateTime|null
     */
    private $endDate;

    public function __construct(?int $limit = 25, ?bool $descending = false)
    {
        $this->limit = $limit;
        $this->descending = $descending;
    }

    public static function fromPurchaseHash(string $purchaseHash, ?int $limit = 25, ?bool $descending = false): self
    {
        $self = new self($limit, $descending);
        $self->lastPurchaseHash = $purchaseHash;

        return $self;
    }

    public static function byDate(DateTime $startDate, DateTime $endDate, ?int $limit = 25, ?bool $descending = false): self
    {
        $self = new self($limit, $descending);
        $self->startDate = $startDate;
        $self->endDate = $endDate;

        return $self;
    }

    public function getParameters(): array
    {
        $queryParameters = [
            'limit' => $this->limit,
            'descending' => $this->descending,
            'lastPurchaseHash' => $this->lastPurchaseHash
        ];

        if ($this->startDate) {
            $queryParameters['startDate'] = $this->startDate->format(DATE_ATOM);
        }

        if ($this->endDate) {
            $queryParameters['endDate'] = $this->endDate->format(DATE_ATOM);
        }

        return $queryParameters;
    }
}
