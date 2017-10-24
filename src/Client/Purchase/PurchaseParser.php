<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use DateTime;
use LauLamanApps\IzettleApi\API\Purchase\Purchase;
use LauLamanApps\IzettleApi\API\Purchase\User;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\Uuid;

final class PurchaseParser
{
    public static function parseArray(array $purchases): array
    {
        $data = [];

        foreach ($purchases as $purchase) {
            $data[] = self::parse($purchase);
        }

        return $data;
    }

    public static function parse(array $purchase): Purchase
    {
        $currency = new Currency($purchase['currency']);

        $coordinates = null;
        if (array_key_exists('gpsCoordinates', $purchase)) {
            $coordinates = CoordinatesParser::parse($purchase['gpsCoordinates']);
        }

        $published = null;
        if (array_key_exists('published', $purchase)) {
            $published =  $purchase['published'];
        }

        return new Purchase(
            $purchase['purchaseUUID'],
            Uuid::fromString($purchase['purchaseUUID1']),
            new DateTime($purchase['timestamp']),
            $coordinates,
            $purchase['country'],
            new User($purchase['userId'], $purchase['userDisplayName']),
            $purchase['organizationId'],
            $purchase['purchaseNumber'],
            new Money($purchase['amount'], $currency),
            new Money($purchase['vatAmount'], $currency),
            ProductParser::parseArray($purchase['products'], $currency),
            PaymentParser::parseArray($purchase['payments'], $currency),
            VatParser::parseArray($purchase['groupedVatAmounts'], $currency),
            $purchase['receiptCopyAllowed'],
            $published,
            $purchase['refund'],
            $purchase['refunded']
        );
    }
}
