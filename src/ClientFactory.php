<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi;

use LauLamanApps\IzettleApi\Client\Product\CategoryBuilder;
use LauLamanApps\IzettleApi\Client\Product\DiscountBuilder;
use LauLamanApps\IzettleApi\Client\Product\LibraryBuilder;
use LauLamanApps\IzettleApi\Client\Product\ProductBuilder as ProductProductBuilder;
use LauLamanApps\IzettleApi\Client\Product\VariantBuilder;
use LauLamanApps\IzettleApi\Client\ProductClient;
use LauLamanApps\IzettleApi\Client\Purchase\CoordinatesBuilder;
use LauLamanApps\IzettleApi\Client\Purchase\PaymentBuilder;
use LauLamanApps\IzettleApi\Client\Purchase\ProductBuilder as PurchaseProductBuilder;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseBuilder;
use LauLamanApps\IzettleApi\Client\Purchase\PurchaseHistoryBuilder;
use LauLamanApps\IzettleApi\Client\Purchase\VatBuilder;
use LauLamanApps\IzettleApi\Client\PurchaseClient;
use LauLamanApps\IzettleApi\Client\Universal\ImageBuilder;
use Ramsey\Uuid\UuidInterface;

final class ClientFactory
{
    public static function getProductClient(IzettleClientInterface $client, ?UuidInterface $organizationUuid = null): ProductClient
    {
        $categoryBuilder = new CategoryBuilder();
        $imageBuilder = new ImageBuilder();
        $variantBuilder = new VariantBuilder();
        $discountBuilder = new DiscountBuilder($imageBuilder);
        $productBuilder = new ProductProductBuilder($categoryBuilder, $imageBuilder, $variantBuilder);
        $libraryBuilder = new LibraryBuilder($productBuilder, $discountBuilder);

        return new ProductClient(
            $client,
            $organizationUuid,
            $categoryBuilder,
            $discountBuilder,
            $libraryBuilder,
            $productBuilder
        );
    }

    public static function getPurchaseClient(IzettleClientInterface $client): PurchaseClient
    {
        $coordinatesBuilder = new CoordinatesBuilder();
        $purchaseProductBuilder = new PurchaseProductBuilder();
        $paymentBuilder = new PaymentBuilder();
        $vatBuilder = new VatBuilder();
        $purchaseBuilder = new PurchaseBuilder($coordinatesBuilder, $purchaseProductBuilder, $paymentBuilder, $vatBuilder);
        $purchaseHistoryBuilder = new PurchaseHistoryBuilder($purchaseBuilder);

        return new PurchaseClient(
            $client,
            $purchaseHistoryBuilder,
            $purchaseBuilder
        );
    }
}
