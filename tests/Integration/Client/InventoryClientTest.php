<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Integration\Client;
use LauLamanApps\IzettleApi\API\Inventory\LocationInventory;
use LauLamanApps\IzettleApi\IzettleClientFactory;

/**
 * @medium
 */
final class InventoryClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getLocations(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/files/InventoryClient/getLocations.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getInventoryClient($iZettleClient);

        $locations = $purchaseClient->getLocations();

        foreach ($locations as $index => $location) {
            self::assertInstanceOf(LocationInventory::class, $location);
            self::assertSame($data[$index]['uuid'], (string) $location->getUuid());
            self::assertSame($data[$index]['name'], $location->getName());
            self::assertSame($data[$index]['type'], $location->getType()->getValue());
            self::assertSame($data[$index]['description'], $location->getDescription());
            self::assertSame($data[$index]['default'], $location->isDefault());
        }
    }

    /**
     * @test
     */
    public function getLocation(): void
    {
        $json = file_get_contents(dirname(__FILE__) . '/files/InventoryClient/getLocation.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $purchaseClient = IzettleClientFactory::getInventoryClient($iZettleClient);

        $location = $purchaseClient->getLocation();

        self::assertInstanceOf(LocationInventory::class, $location);
        self::assertSame($data[$index]['uuid'], (string) $location->getUuid());
        self::assertSame($data[$index]['name'], $location->getName());
        self::assertSame($data[$index]['type'], $location->getType()->getValue());
        self::assertSame($data[$index]['description'], $location->getDescription());
        self::assertSame($data[$index]['default'], $location->isDefault());
    }

    /**
     * @test
     */
    public function getProduct(): void
    {

    }

    /**
     * @test
     */
    public function getHistory(): void
    {

    }

    /**
     * @test
     */
    public function getSettings(): void
    {

    }
}
