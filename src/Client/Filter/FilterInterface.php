<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Filter;

interface FilterInterface
{
    public function getParameters(): array;
}
