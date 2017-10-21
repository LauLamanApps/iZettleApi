<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Client\Purchase;

use LauLamanApps\iZettleApi\API\Purchase\PurchaseHistory;
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
