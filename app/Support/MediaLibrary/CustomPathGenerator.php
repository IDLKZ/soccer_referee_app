<?php

namespace App\Support\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . $media->id . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . $media->id . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . $media->id . '/responsive-images/';
    }

    protected function getBasePath(Media $media): string
    {
        return 'uploads/' . strtolower(class_basename($media->model_type)) . '/' . $media->model_id . '/';
    }
}