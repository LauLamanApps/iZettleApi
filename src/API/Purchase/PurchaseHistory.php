<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API\Purchase;

final class PurchaseHistory
{
    private $firstPurchaseHash;
    private $lastPurchaseHash;
    private $purchases;

    public function __construct(
        string $firstPurchaseHash,
        string $lastPurchaseHash,
        array $purchases
    ) {
        $this->firstPurchaseHash = $firstPurchaseHash;
        $this->lastPurchaseHash = $lastPurchaseHash;

        foreach ($purchases as $purchase) {
            if ($purchase instanceof Purchase) {
                $this->addPurchase($purchase);
            }
        }
    }

    public function getFirstPurchaseHash(): string
    {
        return $this->firstPurchaseHash;
    }

    public function getLastPurchaseHash(): string
    {
        return $this->lastPurchaseHash;
    }
    public function addPurchase(Purchase $purchase): void
    {
        $this->purchases[(string) $purchase->getUuid()] = $purchase;
    }

    public function removePurchase(Purchase $purchase): void
    {
        unset($this->purchases[(string)$purchase->getUuid()]);
    }

    public function getPurchases(): array
    {
        return $this->purchases;
    }
}
