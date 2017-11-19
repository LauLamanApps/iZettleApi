<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client;

use LauLamanApps\IzettleApi\API\Inventory\LocationInventory;
use LauLamanApps\IzettleApi\API\Inventory\ProductBalance;
use LauLamanApps\IzettleApi\API\Inventory\Settings;
use LauLamanApps\IzettleApi\API\Inventory\VariantChangeHistory;
use LauLamanApps\IzettleApi\API\Product\Product;
use LauLamanApps\IzettleApi\Client\Filter\Inventory\HistoryFilter;
use LauLamanApps\IzettleApi\Client\Inventory\VariantChangeHistoryBuilderInterface;
use LauLamanApps\IzettleApi\Client\Inventory\LocationInventoryBuilderInterface;
use LauLamanApps\IzettleApi\Client\Inventory\Post\StartTrackingRequest;
use LauLamanApps\IzettleApi\Client\Inventory\ProductBalanceBuilderInterface;
use LauLamanApps\IzettleApi\Client\Inventory\SettingsBuilderInterface;
use LauLamanApps\IzettleApi\Exception\UnprocessableEntityException;
use LauLamanApps\IzettleApi\IzettleClientInterface;
use Ramsey\Uuid\UuidInterface;

final class InventoryClient
{
    private const DEFAULT_ORGANIZATION_UUID = 'self';

    const BASE_URL = 'https://inventory.izettle.com/organizations/%s';

    const GET_HISTORY = self::BASE_URL . '/history/locations/%s';

    const GET_INVENTORY_LOCATIONS = self::BASE_URL . '/locations';
    const GET_INVENTORY_LOCATION = self::BASE_URL . '/inventory/locations/%s';
    const GET_PRODUCT_INVENTORY = self::BASE_URL . '/inventory/locations/%s/products/%s';
    const POST_INVENTORY = self::BASE_URL . '/inventory';
    const POST_INVENTORY_BULK = self::BASE_URL . '/inventory/bulk';
    const PUT_INVENTORY = self::BASE_URL . '/inventory';
    const DELETE_PRODUCT_INVENTORY = self::BASE_URL . '/inventory/products/%s';

    const GET_LOCATION = self::BASE_URL . '/locations/template';
    const PUT_LOCATIONS = self::BASE_URL . '/locations/%s';

    const GET_SETTINGS = self::BASE_URL . '/settings';
    const POST_SETTINGS = self::BASE_URL . '/settings';
    const PUT_SETTINGS = self::BASE_URL . '/settings';

    /**
     * @var IzettleClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $organizationUuid;

    /**
     * @var LocationInventoryBuilderInterface
     */
    private $locationInventoryBuilder;

    /**
     * @var ProductBalanceBuilderInterface
     */
    private $productBalanceBuilder;

    /**
     * @var VariantChangeHistoryBuilderInterface
     */
    private $variantChangeHistoryBuilder;

    public function __construct(
        IzettleClientInterface $client,
        ?UuidInterface $organizationUuid = null,
        LocationInventoryBuilderInterface $locationInventoryBuilder,
        ProductBalanceBuilderInterface $productBalanceBuilder,
        VariantChangeHistoryBuilderInterface $variantChangeHistoryBuilder
    ) {
        $this->client = $client;
        $this->organizationUuid = $organizationUuid ? $organizationUuid->toString() : self::DEFAULT_ORGANIZATION_UUID;
        $this->locationInventoryBuilder = $locationInventoryBuilder;
        $this->productBalanceBuilder = $productBalanceBuilder;
        $this->variantChangeHistoryBuilder = $variantChangeHistoryBuilder;
    }

    public function setOrganizationUuid(UuidInterface $organizationUuid): void
    {
        $this->organizationUuid = $organizationUuid->toString();
    }

    public function resetOrganizationUuid(): void
    {
        $this->organizationUuid = self::DEFAULT_ORGANIZATION_UUID;
    }

    /**
     * @return LocationInventory[]
     */
    public function getLocationInventories(): array
    {
        $url = sprintf(self::GET_INVENTORY_LOCATIONS, $this->organizationUuid);
        $json = $this->client->getJson($this->client->get($url, null));

        return $this->locationInventoryBuilder->buildFromJsonArray($json);
    }

    public function getLocationInventory(UuidInterface $locationUuid): LocationInventory
    {
        $url = sprintf(self::GET_LOCATION, $this->organizationUuid, $locationUuid->toString());
        $json = $this->client->getJson($this->client->get($url, null));

        return $this->locationInventoryBuilder->buildFromJson($json);
    }

    public function getProductInventory(UuidInterface $locationUuid, UuidInterface $productUuid): ProductBalance
    {
        $url = sprintf(self::GET_PRODUCT_INVENTORY, $this->organizationUuid, $locationUuid->toString(), $productUuid->toString());
        $json = $this->client->getJson($this->client->get($url, null));

        return $this->productBalanceBuilder->buildFromJson($json);
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function trackInventory(Product $product): ProductBalance
    {
        $url = sprintf(self::POST_INVENTORY, $this->organizationUuid);
        $json = $this->client->getJson($this->client->post($url, new StartTrackingRequest($product)));

        return $this->productBalanceBuilder->buildFromJson($json);
    }

    /**
     * @return VariantChangeHistory[]
     */
    public function getHistory(UuidInterface $locationUuid): array
    {
        $url = sprintf(self::GET_HISTORY, $this->organizationUuid, $locationUuid->toString());
        $json = $this->client->getJson($this->client->get($url, new HistoryFilter()));

        return $this->variantChangeHistoryBuilder->buildFromJson($json);
    }
}
