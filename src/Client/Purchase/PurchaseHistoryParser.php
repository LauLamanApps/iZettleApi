<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use Psr\Http\Message\ResponseInterface;

final class PurchaseHistoryParser
{
    public static function createFromResponse(ResponseInterface $response): PurchaseHistory
    {
        $data = json_decode($response->getBody()->getContents(), true);

        return new PurchaseHistory(
            $data['firstPurchaseHash'],
            $data['lastPurchaseHash'],
            PurchaseParser::parseArray($data['purchases'])
        );
    }
}
