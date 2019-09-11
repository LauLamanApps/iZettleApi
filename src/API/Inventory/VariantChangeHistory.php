<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Inventory;

use DateTime;

final class VariantChangeHistory
{
    /**
     * @var string
     */
    private $productUuid;

    /**
     * @var string
     */
    private $variantUuid;

    /**
     * @var int
     */
    private $change;

    /**
     * @var DateTime
     */
    private $changed;

    public function __construct(string $productUuid, string $variantUuid, int $change, DateTime $changed)
    {
        $this->productUuid = $productUuid;
        $this->variantUuid = $variantUuid;
        $this->change = $change;
        $this->changed = $changed;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getVariantUuid(): string
    {
        return $this->variantUuid;
    }

    public function getChange(): int
    {
        return $this->change;
    }

    public function getChanged(): DateTime
    {
        return $this->changed;
    }
}
