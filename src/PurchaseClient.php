<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi;

use LauLamanApps\IzettleApi\API\Purchase\Purchase;
use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseHistoryParser;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseParser;
use Ramsey\Uuid\UuidInterface;

final class PurchaseClient extends AbstractClient
{
    const BASE_URL = 'https://purchase.izettle.com';

    const GET_PURCHASE = self::BASE_URL . '/purchases/v2/%s';
    const GET_PURCHASES = self::BASE_URL . '/purchases/v2';

    public function getPurchaseHistory(): PurchaseHistory
    {
        return PurchaseHistoryParser::createFromResponse($this->get(self::GET_PURCHASES));
    }

    public function getPurchase(UuidInterface $uuid): Purchase
    {
        $url = sprintf(self::GET_PURCHASE, (string) $uuid);

        return PurchaseParser::createFromResponse($this->get($url));
    }
}
