<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use Psr\Http\Message\ResponseInterface;

final class PurchaseHistoryBuilder implements PurchaseHistoryBuilderInterface
{
    private $purchaseBuilder;

    public function __construct(PurchaseBuilderInterface $purchaseBuilder)
    {
        $this->purchaseBuilder = $purchaseBuilder;
    }

    public function buildFromJson(string $jsonData): PurchaseHistory
    {
        $data =  json_decode($jsonData, true);

        dump($data);

        return new PurchaseHistory(
            $data['firstPurchaseHash'],
            $data['lastPurchaseHash'],
            $this->purchaseBuilder->buildFromArray($data['purchases'])
        );
    }
}
