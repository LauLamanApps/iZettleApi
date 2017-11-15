<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Product;

use DateTime;
use LauLamanApps\IzettleApi\API\ImageCollection;
use LauLamanApps\IzettleApi\API\Universal\IzettlePostable;
use LauLamanApps\IzettleApi\Client\Exception\CantCreateProductException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Product implements IzettlePostable
{
    private $uuid;
    private $categories;
    private $name;
    private $description;
    private $imageCollection;
    private $variants;
    private $externalReference;
    private $etag;
    private $updatedAt;
    private $updatedBy;
    private $createdAt;
    private $vatPercentage;

    public static function create(
        UuidInterface $uuid,
        CategoryCollection $categories,
        string $name,
        ?string $description = null,
        ImageCollection $imageCollection,
        VariantCollection $variants,
        ?string $externalReference =  null,
        string $etag,
        DateTime $updatedAt,
        UuidInterface $updatedBy,
        DateTime $createdAt,
        float $vatPercentage
    ): self {
        return new self(
            $uuid,
            $categories,
            $name,
            $description,
            $imageCollection,
            $variants,
            $externalReference,
            $etag,
            $updatedAt,
            $updatedBy,
            $createdAt,
            $vatPercentage
        );
    }

    public static function new(
        string $name,
        string $description,
        CategoryCollection $categories,
        ImageCollection $imageCollection,
        VariantCollection $variants,
        ?string $externalReference = null
    ): self {
        return new self(
            Uuid::uuid1(),
            $categories,
            $name,
            $description,
            $imageCollection,
            $variants,
            $externalReference
        );
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getCategories(): CategoryCollection
    {
        return $this->categories;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImageLookupKeys(): ImageCollection
    {
        return $this->imageCollection;
    }

    public function getVariants(): VariantCollection
    {
        return $this->variants;
    }

    public function getExternalReference(): ?string
    {
        return $this->externalReference;
    }

    public function getEtag(): string
    {
        return $this->etag;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getUpdatedBy(): UuidInterface
    {
        return $this->updatedBy;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getVatPercentage(): float
    {
        return $this->vatPercentage;
    }

    public function getPostBodyData(): string
    {
        $this->validateMinimumVariants();

        $data = [
            'uuid' => $this->uuid,
            'categories' => $this->categories->getCreateDataArray(),
            'name' => $this->name,
            'description' => $this->description,
            'imageLookupKeys' => $this->imageCollection->getCreateDataArray(),
            'variants' => $this->variants->getCreateDataArray(),
            'externalReference' => $this->externalReference
        ];

        return json_encode($data);
    }

    private function __construct(
        UuidInterface $uuid,
        CategoryCollection $categories,
        string $name,
        ?string $description,
        ImageCollection $imageCollection,
        VariantCollection $variants,
        ?string $externalReference = null,
        ?string $etag = null,
        ?DateTime $updatedAt  = null,
        ?UuidInterface $updatedBy = null,
        ?DateTime $createdAt = null,
        ?float $vatPercentage = null
    ) {
        $this->uuid = $uuid;
        $this->categories = $categories;
        $this->name = $name;
        $this->description = $description;
        $this->imageCollection = $imageCollection;
        $this->variants = $variants;
        $this->externalReference = $externalReference;
        $this->etag = $etag;
        $this->updatedAt = $updatedAt;
        $this->updatedBy = $updatedBy;
        $this->createdAt = $createdAt;
        $this->vatPercentage = $vatPercentage;
    }

    private function validateMinimumVariants(): void
    {
        if (count($this->variants->getAll()) == 0) {
            throw new CantCreateProductException('A product should have at least one variant');
        }
    }
}
