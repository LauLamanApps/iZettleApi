<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Product;

use LauLamanApps\IzettleApi\API\Universal\Vat;
use Money\Money;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Variant
{

    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $sku;

    /**
     * @var string|null
     */
    private $barcode;

    /**
     * @var int
     */
    private $defaultQuantity;

    /**
     * @var string|null
     */
    private $unitName;

    /**
     * @var Money
     */
    private $price;

    /**
     * @var Money|null
     */
    private $costPrice;

    /**
     * @var Vat|null
     */
    private $vat;


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
        ?Vat $vat = null
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
            $vat
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
        ?Vat $vat = null
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
            $vat
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

    public function getVat(): ?Vat
    {
        return $this->vat;
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
            'vatPercentage' => $this->getVat() ? $this->getVat()->getPercentage() : '0'
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
        ?Vat $vat = null
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
        $this->vat = $vat;
    }
}
