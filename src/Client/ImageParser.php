<?php

declare(strict_types=1);

namespace LauLamanApps\iZettleApi\Client;

use LauLamanApps\iZettleApi\API\Image;
use LauLamanApps\iZettleApi\API\ImageCollection;

final class ImageParser
{
    public static function parseArray(array $images)
    {
        $collection = new ImageCollection();

        foreach ($images as $image) {
            $collection->add(self::parse($image));
        }

        return $collection;
    }

    private static function parse($data): Image
    {
        return new Image($data);
    }
}
