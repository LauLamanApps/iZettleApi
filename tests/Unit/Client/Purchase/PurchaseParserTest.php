<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Tests\Unit\Client\Purchase;

use DateTime;
use LauLamanApps\iZettleApi\API\Purchase\Coordinates;
use LauLamanApps\iZettleApi\API\Purchase\Purchase;
use LauLamanApps\iZettleApi\Client\Purchase\PurchaseParser;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class PurchaseParserTest extends TestCase
{
    /**
     * @test
     */
    public function parse(): void
    {
        $data = $this->getData();

        $purchase = PurchaseParser::parse($data);

        self::assertInstanceOf(Purchase::class, $purchase);
        self::assertSame($data['purchaseUUID'], $purchase->getUuid());
        self::assertSame($data['purchaseUUID1'], (string) $purchase->getUuid1());
        self::assertSame($data['amount'], (int) $purchase->getAmount()->getAmount());
        self::assertSame($data['currency'], $purchase->getAmount()->getCurrency()->getCode());
        self::assertSame($data['vatAmount'], (int) $purchase->getVatAmount()->getAmount());
        self::assertInstanceOf(Coordinates::class, $purchase->getCoordinates());
        self::assertSame($data['country'], $purchase->getCountry());
        self::assertEquals(new DateTime($data['timestamp']), $purchase->getTimestamp());
        self::assertSame($data['purchaseNumber'], $purchase->getPurchaseNumber());
        self::assertSame($data['userDisplayName'], (string) $purchase->getUser());
        self::assertSame($data['userId'], $purchase->getUser()->getId());
        self::assertSame($data['organizationId'], $purchase->getOrganizationId());
        self::assertSame($data['receiptCopyAllowed'], $purchase->isReceiptCopyAllowed());
        self::assertSame($data['published'], $purchase->getPublished());
        self::assertSame($data['refund'], $purchase->isRefund());
        self::assertSame($data['refunded'], $purchase->isRefunded());
    }

    /**
     * @test
     */
    public function parseArray()
    {
        $data[] = $this->getData();
        $data[] = $this->getData();

        $purchases = PurchaseParser::parseArray($data);

        self::assertSame(count($data), count($purchases));

        foreach ($purchases as $purchase) {
            self::assertInstanceOf(Purchase::class, $purchase);
        }
    }

    public function getData(): array
    {
        return [
            "purchaseUUID" => "ShNaGb-nSiMAJPESKGynSG",
            "purchaseUUID1" => (string) Uuid::uuid1(),
            "amount" => 100,
            "vatAmount" => 17,
            "country" => "NL",
            "currency" => "EUR",
            "timestamp" => "2016-05-01T14:31:29.748+0000",
            "gpsCoordinates" => ['latitude' => 0, 'longitude' => 0, 'accuracyMeters' => 0],
            "purchaseNumber" => 1,
            "userDisplayName" => "John Doe",
            "userId" => 12766,
            "organizationId" => 897184,
            "products" => [],
            "payments" => [],
            "receiptCopyAllowed" => true,
            "published" => true,
            "groupedVatAmounts" => [],
            "refund" => false,
            "refunded" => false
        ];
    }
}
