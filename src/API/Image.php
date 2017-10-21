<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\API;

final class Image
{
    const BASE_URL = 'https://image.izettle.com/productimage/';

    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getSmallImageUrl(): string
    {
        return self::BASE_URL . 'L/' . $this->getFilename();
    }

    public function getLargeImageUrl(): string
    {
        return self::BASE_URL . 'o/' . $this->getFilename();
    }
}
