<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Product;

use Money\Money;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Variant
{
    private $uuid;
    private $name;
    private $description;
    private $sku;
    private $barcode;
    private $defaultQuantity;
    private $unitName;
    private $price;
    private $costPrice;
    private $vatPercentage;

    public static function create(
        UuidInterface $uuid,
        ?string $name = null,
        ?string $description = null,
        ?string $sku = null,
        ?string $barcode = null,
        int $defaultQuantity,
        ?string $unitName = null,
        Money $price,
        ?Money $costPrice = null,
        float $vatPercentage
    ): self {
        return new self(
            $uuid,
            $name,
            $description,
            $sku,
            $barcode,
            $defaultQuantity,
            $unitName,
            $price,
            $costPrice,
            $vatPercentage
        );
    }

    public static function new(
        ?string $name = null,
        ?string $description = null,
        ?string $sku = null,
        ?string $barcode = null,
        int $defaultQuantity,
        ?string $unitName = null,
        Money $price,
        ?Money $costPrice = null,
        float $vatPercentage
    ): self {
        return new self(
            Uuid::uuid1(),
            $name,
            $description,
            $sku,
            $barcode,
            $defaultQuantity,
            $unitName,
            $price,
            $costPrice,
            $vatPercentage
        );
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function getDefaultQuantity(): ?int
    {
        return $this->defaultQuantity;
    }

    public function getUnitName(): ?string
    {
        return $this->unitName;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getCostPrice(): ?Money
    {
        return $this->costPrice;
    }

    public function getVatPercentage(): float
    {
        return $this->vatPercentage;
    }

    public function getCreateDataArray(): array
    {
        $data = [
            'uuid' => $this->getUuid(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'sku' => $this->getSku(),
            'barcode' => $this->getBarcode(),
            'defaultQuantity' => $this->getDefaultQuantity(),
            'unitName' => $this->getUnitName(),
            'price' => [
                'amount' => $this->price->getAmount(),
                'currencyId' => (string) $this->price->getCurrency(),
            ],
            'vatPercentage' => $this->getVatPercentage()
        ];

        if ($this->costPrice) {
            $data['costPrice'] =[
                'amount' => $this->costPrice->getAmount(),
                'currencyId' => (string) $this->costPrice->getCurrency()
            ];
        }

        return $data;
    }

    private function __construct(
        UuidInterface $uuid,
        ?string $name,
        ?string $description,
        ?string $sku,
        ?string $barcode,
        int $defaultQuantity,
        ?string $unitName,
        Money $price,
        ?Money $costPrice,
        float $vatPercentage
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->sku = $sku;
        $this->barcode = $barcode;
        $this->defaultQuantity = $defaultQuantity;
        $this->unitName = $unitName;
        $this->price = $price;
        $this->costPrice = $costPrice;
        $this->vatPercentage = $vatPercentage;
    }
}
