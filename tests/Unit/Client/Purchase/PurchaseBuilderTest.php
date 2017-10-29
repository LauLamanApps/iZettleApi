<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client\Purchase;

use DateTime;
use LauLamanApps\IzettleApi\API\Purchase\Coordinates;
use LauLamanApps\IzettleApi\API\Purchase\Purchase;
use LauLamanApps\IzettleApi\Client\Purchase\CoordinatesBuilderInterface;
use LauLamanApps\IzettleApi\Client\Purchase\PaymentBuilderInterface;
use LauLamanApps\IzettleApi\Client\Purchase\ProductBuilderInterface;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseBuilder;
use LauLamanApps\IzettleApi\Client\Purchase\VatBuilderInterface;
use Mockery;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class PurchaseBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function buildFromJson(): void
    {
        $data = $this->getDataSet1();
        $coordinatesBuilderMock = Mockery::mock(CoordinatesBuilderInterface::class);
        $coordinatesBuilderMock->shouldReceive('buildFromArray')->once()->with($data['gpsCoordinates'])->andReturn(new Coordinates(0, 0, 0));
        $productBuilderMock =  Mockery::mock(ProductBuilderInterface::class);
        $productBuilderMock->shouldReceive('buildFromArray')->once()->with($data['products'], Mockery::type((Currency::class)));
        $paymentBuilderMock =  Mockery::mock(PaymentBuilderInterface::class);
        $paymentBuilderMock->shouldReceive('buildFromArray')->once()->with($data['payments'], Mockery::type((Currency::class)));
        $vatBuilderMock =  Mockery::mock(VatBuilderInterface::class);
        $vatBuilderMock->shouldReceive('buildFromArray')->once()->with($data['groupedVatAmounts'], Mockery::type((Currency::class)));

        $builder = new PurchaseBuilder($coordinatesBuilderMock, $productBuilderMock, $paymentBuilderMock, $vatBuilderMock);
        $purchase = $builder->buildFromJson(json_encode($data));

        self::assertInstanceOf(Purchase::class, $purchase);
        self::assertSame($data['purchaseUUID'], $purchase->getUuid());
        self::assertSame($data['purchaseUUID1'], (string) $purchase->getUuid1());
        self::assertSame($data['amount'], (int) $purchase->getAmount()->getAmount());
        self::assertSame($data['currency'], $purchase->getAmount()->getCurrency()->getCode());
        self::assertSame($data['vatAmount'], (int) $purchase->getVatAmount()->getAmount());
        self::assertSame($data['country'], $purchase->getCountry());
        self::assertEquals(new DateTime($data['timestamp']), $purchase->getTimestamp());
        self::assertInstanceOf(Coordinates::class, $purchase->getCoordinates());
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
    public function buildFromJsonArray()
    {
        $data[] = $this->getDataSet1();
        $data[] = $this->getDataSet2();

        $count =  count($data);
        $coordinatesBuilderMock = Mockery::mock(CoordinatesBuilderInterface::class);
        $coordinatesBuilderMock->shouldReceive('buildFromArray')->times($count)->andReturn(new Coordinates(0, 0, 0));
        $productBuilderMock =  Mockery::mock(ProductBuilderInterface::class);
        $productBuilderMock->shouldReceive('buildFromArray')->times($count);
        $paymentBuilderMock =  Mockery::mock(PaymentBuilderInterface::class);
        $paymentBuilderMock->shouldReceive('buildFromArray')->times($count);
        $vatBuilderMock =  Mockery::mock(VatBuilderInterface::class);
        $vatBuilderMock->shouldReceive('buildFromArray')->times();

        $builder = new PurchaseBuilder($coordinatesBuilderMock, $productBuilderMock, $paymentBuilderMock, $vatBuilderMock);
        $purchases = $builder->buildFromArray($data);

        self::assertSame(count($data), count($purchases));

        foreach ($purchases as $purchase) {
            self::assertInstanceOf(Purchase::class, $purchase);
        }
    }

    public function getDataSet1(): array
    {
        return [
            "purchaseUUID" => "ShNaGb-nSiMAJPESKGynSG",
            "purchaseUUID1" => (string) Uuid::uuid1(),
            "amount" => 100,
            "vatAmount" => 17,
            "country" => "NL",
            "currency" => "EUR",
            "timestamp" => "2016-05-01T14:31:29.748+0000",
            "gpsCoordinates" => [(string) Uuid::uuid1()],
            "purchaseNumber" => 1,
            "userDisplayName" => "John Doe",
            "userId" => 12766,
            "organizationId" => 897184,
            "products" => [(string) Uuid::uuid1()],
            "payments" => [(string) Uuid::uuid1()],
            "receiptCopyAllowed" => true,
            "published" => true,
            "groupedVatAmounts" => [(string) Uuid::uuid1()],
            "refund" => false,
            "refunded" => false
        ];
    }

    public function getDataSet2(): array
    {
        return [
            "purchaseUUID" => "ShNaGb-nSiMAJPESKGynSG",
            "purchaseUUID1" => (string) Uuid::uuid1(),
            "amount" => 100,
            "vatAmount" => 17,
            "country" => "NL",
            "currency" => "EUR",
            "timestamp" => "2016-05-01T14:31:29.748+0000",
            "purchaseNumber" => 1,
            "userDisplayName" => "John Doe",
            "userId" => 12766,
            "organizationId" => 897184,
            "products" => [(string) Uuid::uuid1()],
            "payments" => [(string) Uuid::uuid1()],
            "receiptCopyAllowed" => true,
            "groupedVatAmounts" => [(string) Uuid::uuid1()],
            "refund" => false,
            "refunded" => false
        ];
    }
}
