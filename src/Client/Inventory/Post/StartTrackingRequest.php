<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Inventory\Post;

use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\API\Universal\IzettlePostable;
use Ramsey\Uuid\UuidInterface;

final class StartTrackingRequest implements IzettlePostable
{
    /**
     * @var UuidInterface
     */
    private $productUuid;

    public function __construct(Product $product)
    {
        $this->productUuid = $product->getUuid();
    }

    public function getPostBodyData(): string
    {
        return json_encode(['productUuid' => $this->productUuid->toString()]);
    }

}
