<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Purchase;

use DateTime;
use LauLamanApps\IzettleApi\API\Purchase\Coordinates;
use LauLamanApps\IzettleApi\API\Purchase\Purchase;
use LauLamanApps\IzettleApi\API\Purchase\PurchaseHistory;
use LauLamanApps\IzettleApi\API\Purchase\User;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\Uuid;

final class PurchaseBuilder implements PurchaseBuilderInterface
{
    private $coordinatesBuilder;
    private $productBuilder;
    private $paymentBuilder;
    private $vatBuilder;

    public function __construct(
        CoordinatesBuilderInterface $coordinatesBuilder,
        ProductBuilderInterface $productBuilder,
        PaymentBuilderInterface $paymentBuilder,
        VatBuilderInterface $vatBuilder
    ) {
        $this->coordinatesBuilder = $coordinatesBuilder;
        $this->productBuilder = $productBuilder;
        $this->paymentBuilder = $paymentBuilder;
        $this->vatBuilder = $vatBuilder;
    }

    /**
     * @return Purchase[]
     */
    public function buildFromArray(array $purchases): array
    {
        $data = [];

        foreach ($purchases as $purchase) {
            $data[] = $this->build($purchase);
        }

        return $data;
    }

    public function buildFromJson(string $jsonData): Purchase
    {
        $data = json_decode($jsonData, true);

        return $this->build($data);
    }

    public function build(array $data): Purchase
    {
        $currency = new Currency($data['currency']);

        return new Purchase(
            $data['purchaseUUID'],
            Uuid::fromString($data['purchaseUUID1']),
            new DateTime($data['timestamp']),
            $this->getCoordinatesFromKey('gpsCoordinates', $data),
            $data['country'],
            new User($data['userId'], $data['userDisplayName']),
            $data['organizationId'],
            $data['purchaseNumber'],
            new Money($data['amount'], $currency),
            new Money($data['vatAmount'], $currency),
            $this->productBuilder->buildFromArray($data['products'], $currency),
            $this->paymentBuilder->buildFromArray($data['payments'], $currency),
            $this->vatBuilder->buildFromArray($data['groupedVatAmounts'], $currency),
            $data['receiptCopyAllowed'],
            $this->getBoolFromKey('published', $data),
            $data['refund'],
            $data['refunded']
        );
    }

    private function getBoolFromKey($key, array $data): ?bool
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        return $data[$key];
    }

    private function getCoordinatesFromKey($key, array $data): ?Coordinates
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        return $this->coordinatesBuilder->buildFromArray($data[$key]);
    }
}
