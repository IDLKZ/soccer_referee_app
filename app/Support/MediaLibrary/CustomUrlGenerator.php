<?php

namespace App\Support\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class CustomUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $media = $this->media;
        $path = 'storage/' . $this->getBasePath($media) . $media->id . '/' . $media->file_name;

        return $path;
    }

    protected function getBasePath(Media $media): string
    {
        return 'uploads/' . strtolower(class_basename($media->model_type)) . '/' . $media->model_id . '/';
    }
}