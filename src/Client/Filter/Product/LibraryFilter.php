<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Filter\Product;

use LauLamanApps\IzettleApi\Client\Filter\FilterInterface;

final class LibraryFilter implements FilterInterface
{
    /**
     * @var string|null
     */
    private $eventLogUuid;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string|null
     */
    private $offset;

    public function __construct(?int $limit = 500, ?string $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public static function fromEventLogUuid(string $eventLogUuid, ?int $limit = 500, ?string $offset = null): self
    {
        $self = new self($limit, $offset);
        $self->eventLogUuid = $eventLogUuid;

        return $self;
    }

    public function getParameters(): array
    {
        return [
             'eventLogUuid' => $this->eventLogUuid,
             'limit' => $this->limit,
             'offset' => $this->offset,
         ];
    }
}
