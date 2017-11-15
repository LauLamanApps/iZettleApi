<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\API\Universal;

interface IzettlePostable
{
    public function getPostBodyData(): string;
}
