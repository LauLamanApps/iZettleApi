<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\ImageCollection;
use Money\Money;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Discount
{
    private $uuid;
    private $name;
    private $description;
    private $imageCollection;
    private $amount;
    private $percentage;
    private $externalReference;
    private $etag;
    private $updatedAt;
    private $updatedBy;
    private $createdAt;

    public static function create(
        UuidInterface $uuid,
        string $name,
        string $description,
        ImageCollection $imageCollection,
        ?Money $amount = null,
        ?float $percentage = null,
        string $externalReference,
        string $etag,
        DateTime $updatedAt,
        UuidInterface $updatedBy,
        DateTime $createdAt
    ): self {
        return new self(
            $uuid,
            $name,
            $description,
            $imageCollection,
            $amount,
            $percentage,
            $externalReference,
            $etag,
            $updatedAt,
            $updatedBy,
            $createdAt
        );
    }

    public static function new(
        string $name,
        string $description,
        ImageCollection $imageCollection,
        ?Money $amount = null,
        ?float $percentage = null,
        ?string $externalReference = null
    ): self {
        return new self(
            Uuid::uuid1(),
            $name,
            $description,
            $imageCollection,
            $amount,
            $percentage,
            $externalReference
        );
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImageCollection(): ?ImageCollection
    {
        return $this->imageCollection;
    }

    public function getAmount(): ?Money
    {
        return $this->amount;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function getExternalReference(): ?string
    {
        return $this->externalReference;
    }

    public function getEtag(): ?string
    {
        return $this->etag;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getUpdatedBy(): ?UuidInterface
    {
        return $this->updatedBy;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }


    private function __construct(
        UuidInterface $uuid,
        string $name,
        string $description,
        ?ImageCollection $imageCollection = null,
        ?Money $amount = null,
        ?float $percentage = null,
        ?string $externalReference = null,
        ?string $etag = null,
        ?DateTime $updatedAt = null,
        ?UuidInterface $updatedBy = null,
        ?DateTime $createdAt = null
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->imageCollection = $imageCollection;
        $this->amount = $amount;
        $this->percentage = $percentage;
        $this->externalReference = $externalReference;
        $this->etag = $etag;
        $this->updatedAt = $updatedAt;
        $this->updatedBy = $updatedBy;
        $this->createdAt = $createdAt;
    }

    public function getCreateData(): string
    {
        $data = [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'imageLookupKeys' => $this->imageCollection->getCreateDataArray(),
            'externalReference' => $this->externalReference
        ];

        if ($this->amount) {
            $data['amount'] = [
                'amount' => $this->amount->getAmount(),
                'currencyId' => (string) $this->amount->getCurrency(),
            ];
        }

        if ($this->percentage) {
            $data['percentage'] = (string) $this->percentage;
        }

        return json_encode($data);
    }
}
