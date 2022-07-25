<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client;

use LauLamanApps\IzettleApi\Client\ApiScope;
use LauLamanApps\IzettleApi\Client\ApiScope\Rights;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class ApiScopeTest extends TestCase
{
    /**
     * @test
     * @dataProvider getRights
     */
    public function setFinancesScope(Rights $rights): void
    {
        $apiScope = new ApiScope();
        $apiScope->setFinancesScope($rights);

        self::assertContains($rights->value, $apiScope->getUrlParameters());
        self::assertContains('FINANCE', $apiScope->getUrlParameters());
    }

    /**
     * @test
     * @dataProvider getRights
     */
    public function setPurchaseScope(Rights $rights): void
    {
        $apiScope = new ApiScope();
        $apiScope->setPurchaseScope($rights);

        self::assertContains($rights->value, $apiScope->getUrlParameters());
        self::assertContains('PURCHASE', $apiScope->getUrlParameters());
    }

    /**
     * @test
     * @dataProvider getRights
     */
    public function setProductScope(Rights $rights): void
    {
        $apiScope = new ApiScope();
        $apiScope->setProductScope($rights);

        self::assertContains($rights->value, $apiScope->getUrlParameters());
        self::assertContains('PRODUCT', $apiScope->getUrlParameters());
    }

    /**
     * @test
     * @dataProvider getRights
     */
    public function setInventoryScope(Rights $rights): void
    {
        $apiScope = new ApiScope();
        $apiScope->setInventoryScope($rights);

        self::assertContains($rights->value, $apiScope->getUrlParameters());
        self::assertContains('INVENTORY', $apiScope->getUrlParameters());
    }

    /**
     * @test
     * @dataProvider getRights
     */
    public function setImageScope(Rights $rights): void
    {
        $apiScope = new ApiScope();
        $apiScope->setImageScope($rights);

        $this->assertStringContainsString($rights->value, $apiScope->getUrlParameters());
        $this->assertStringContainsString('IMAGE', $apiScope->getUrlParameters());
    }

    public function getRights(): array
    {
        $rights = [];
        foreach (Rights::cases() as $right) {
            $rights[$right->value] = [$right];
        }

        return $rights;
    }
}
