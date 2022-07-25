<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Api\Product;

use LauLamanApps\IzettleApi\API\Product\Category;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class CategoryTest extends TestCase
{
    /**
     * @test
     */
    public function new(): void
    {
        $name = 'name';
        $discount = Category::new($name);

        $createData = json_decode($discount->getPostBodyData(), true);
        $this->assertTrue(Uuid::isValid($createData['uuid']));
        $this->assertSame($name, $createData['name']);
    }
}
