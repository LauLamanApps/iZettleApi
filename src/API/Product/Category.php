<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Product;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Category
{
    private $uuid;
    private $name;
    private $etag;
    private $updatedAt;
    private $updatedBy;
    private $createdAt;

    public static function create(
        UuidInterface $uuid,
        string $name,
        string $etag = null,
        DateTime $updatedAt = null,
        UuidInterface $updatedBy = null,
        DateTime $createdAt = null
    ): self {
        return new self(
            $uuid,
            $name,
            $etag,
            $updatedAt,
            $updatedBy,
            $createdAt
        );
    }

    public static function new(string $name): self
    {
        return new self(
            Uuid::uuid1(),
            $name
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

    public function getCreateData(): string
    {
        $data = [
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];

        return json_encode($data);
    }

    private function __construct(
        UuidInterface $uuid,
        string $name,
        ?string $etag = null,
        ?DateTime $updatedAt = null,
        ?UuidInterface $updatedBy = null,
        ?DateTime $createdAt = null
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->etag = $etag;
        $this->updatedAt = $updatedAt;
        $this->updatedBy = $updatedBy;
        $this->createdAt = $createdAt;
    }
}
