<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Inventory\Location;

use Werkspot\Enum\AbstractEnum;

/**
 * @method static self supplier()
 * @method bool isSupplier()
 * @method static self store()
 * @method bool isStore()
 * @method static self sold()
 * @method bool isSold()
 * @method static self bin()
 * @method bool isBin()
 */
final class TypeEnum extends AbstractEnum
{
    private const SUPPLIER = 'SUPPLIER';
    private const STORE = 'STORE';
    private const SOLD = 'SOLD';
    private const BIN = 'BIN';
}
