<?php

namespace LauLamanApps\IzettleApi\Client\Universal;

interface ImageBuilderInterface extends BuilderInterface
{
    public function buildFromArray(array $images);
}
