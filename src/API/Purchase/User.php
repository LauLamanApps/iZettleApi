<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Purchase;

final class User
{
    private $id;
    private $displayName;

    public function __construct(int $id, string $displayName)
    {
        $this->id = $id;
        $this->displayName = $displayName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function __toString(): string
    {
        return $this->getDisplayName();
    }
}
