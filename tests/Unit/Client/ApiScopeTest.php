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

        $this->assertStringContainsString($rights->value, $apiScope->getUrlParameters());
        $this->assertStringContainsString('FINANCE', $apiScope->getUrlParameters());
    }

    /**
     * @test
     * @dataProvider getRights
     */
    public function setPurchaseScope(Rights $rights): void
    {
        $apiScope = new ApiScope();
        $apiScope->setPurchaseScope($rights);

        $this->assertStringContainsString($rights->value, $apiScope->getUrlParameters());
        $this->assertStringContainsString('PURCHASE', $apiScope->getUrlParameters());
    }

    /**
     * @test
     * @dataProvider getRights
     */
    public function setProductScope(Rights $rights): void
    {
        $apiScope = new ApiScope();
        $apiScope->setProductScope($rights);

        $this->assertStringContainsString($rights->value, $apiScope->getUrlParameters());
        $this->assertStringContainsString('PRODUCT', $apiScope->getUrlParameters());
    }

    /**
     * @test
     * @dataProvider getRights
     */
    public function setInventoryScope(Rights $rights): void
    {
        $apiScope = new ApiScope();
        $apiScope->setInventoryScope($rights);

        $this->assertStringContainsString($rights->value, $apiScope->getUrlParameters());
        $this->assertStringContainsString('INVENTORY', $apiScope->getUrlParameters());
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
