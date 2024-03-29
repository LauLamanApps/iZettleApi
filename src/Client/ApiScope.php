<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client;

use LauLamanApps\IzettleApi\Client\ApiScope\Rights;

final class ApiScope
{
    private const FINANCE = 'FINANCE';
    private const PURCHASE = 'PURCHASE';
    private const PRODUCT = 'PRODUCT';
    private const INVENTORY = 'INVENTORY';
    private const IMAGE = 'IMAGE';

    /**
     * @var Rights
     */
    private $finance;

    /**
     * @var Rights
     */
    private $purchase;

    /**
     * @var Rights
     */
    private $product;

    /**
     * @var Rights
     */
    private $inventory;

    /**
     * @var Rights
     */
    private $image;

    public function setFinancesScope(Rights $rights): void
    {
        $this->finance = $rights;
    }

    public function setPurchaseScope(Rights $rights): void
    {
        $this->purchase = $rights;
    }

    public function setProductScope(Rights $rights): void
    {
        $this->product = $rights;
    }

    public function setInventoryScope(Rights $rights): void
    {
        $this->inventory = $rights;
    }

    public function setImageScope(Rights $rights): void
    {
        $this->image = $rights;
    }

    public function getUrlParameters(): string
    {
        $scope = [];
        if ($this->finance !== null) {
            $scope[] = $this->finance->value . ':' . self::FINANCE;
            if ($this->finance->value == Rights::WRITE) {
                $scope[] = Rights::READ . ':' . self::FINANCE;
            }
        }

        if ($this->purchase !== null) {
            $scope[] = $this->purchase->value . ':' . self::PURCHASE;
            if ($this->purchase->value == Rights::WRITE) {
                $scope[] = Rights::READ . ':' . self::PURCHASE;
            }
        }

        if ($this->product !== null) {
            $scope[] = $this->product->value . ':' . self::PRODUCT;
            if ($this->product->value == Rights::WRITE) {
                $scope[] = Rights::READ . ':' . self::PRODUCT;
            }
        }

        if ($this->inventory !== null) {
            $scope[] = $this->inventory->value . ':' . self::INVENTORY;
            if ($this->inventory->value == Rights::WRITE) {
                $scope[] = Rights::READ . ':' . self::INVENTORY;
            }
        }

        if ($this->image !== null) {
            $scope[] = $this->image->value . ':' . self::IMAGE;
            if ($this->image->value == Rights::WRITE) {
                $scope[] = Rights::READ . ':' . self::IMAGE;
            }
        }

        return implode(' ', $scope);
    }
}
