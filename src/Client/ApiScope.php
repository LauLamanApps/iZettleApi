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
            $scope[] = self::FINANCE . ':' . $this->finance->getValue();
        }
        if ($this->purchase !== null) {
            $scope[] = self::PURCHASE . ':' . $this->purchase->getValue();
        }
        if ($this->product !== null) {
            $scope[] = self::PRODUCT . ':' . $this->product->getValue();
        }
        if ($this->inventory !== null) {
            $scope[] = self::INVENTORY . ':' . $this->inventory->getValue();
        }
        if ($this->image !== null) {
            $scope[] = self::IMAGE . ':' . $this->image->getValue();
        }

        return implode(' ', $scope);
    }
}
