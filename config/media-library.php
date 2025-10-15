<?php

return [
    /*
     * The disk on which to store added files and derived images by default.
     */
    'disk_name' => 'public',

    /*
     * The queue connections that will be used by the package to generate conversions.
     */
    'queue_connection_name' => null,

    /*
     * The queue names that will be used by the package to generate conversions.
     */
    'queue_name' => null,

    /*
     * The paths where files will be stored.
     */
    'path_generator' => \App\Support\MediaLibrary\CustomPathGenerator::class,

    /*
     * When urls to files get generated, this class will be called.
     */
    'url_generator' => \App\Support\MediaLibrary\CustomUrlGenerator::class,

    /*
     * Whether to convert all images to the defined conversions.
     */
    'auto_convert' => true,

    /*
     * Whether to generate conversions for a media item when it's created.
     */
    'generate_conversions_on_create' => true,

    /*
     * The engine that should perform the image conversions.
     */
    'image_driver' => env('IMAGE_DRIVER', 'gd'),

    /*
     * The maximum file size of an item in bytes.
     * Adding a larger file will result in an exception.
     */
    'max_file_size' => 1024 * 1024 * 10, // 10MB
];