<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Client\Universal;

use LauLamanApps\IzettleApi\API\Image;
use LauLamanApps\IzettleApi\API\ImageCollection;

final class ImageBuilder implements ImageBuilderInterface
{
    public function buildFromJson(string $json): Image
    {
        $data = json_decode($json, true);

        return new Image($data['imageLookupKey']);
    }

    public function buildFromArray(array $images): ImageCollection
    {
        $collection = new ImageCollection();

        foreach ($images as $image) {
            $collection->add($this->build($image));
        }

        return $collection;
    }

    private function build($data): Image
    {
        return new Image($data);
    }
}
