<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit;

use Exception;
use Mockery;

trait MockeryAssertionTrait
{
    /**
     * @before
     * @return void
     */
    public function setupMockeryContainer(): void
    {
        Mockery::resetContainer();
    }

    /**
     * @after
     */
    public function tearDownMockeryContainer(): void
    {
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        Mockery::close();
    }

    abstract public function addToAssertionCount(int $count): void;
}
