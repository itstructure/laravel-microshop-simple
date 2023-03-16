<?php

namespace App\Services\Uploader\Helpers;

use Exception;
use Imagine\Image\ImageInterface;
use App\Services\Uploader\Classes\ThumbConfig;

class ThumbHelper
{
    public static function configureThumb(string $alias, array $config): ThumbConfig
    {
        if (!isset($config['name']) ||
            !isset($config['size']) ||
            !is_array($config['size']) ||
            (!isset($config['size'][0]) && !is_null($config['size'][0])) ||
            (!isset($config['size'][1]) && !is_null($config['size'][1]))
        ) {
            throw new Exception('Error in thumb configuration.');
        }

        return new ThumbConfig(
            $alias,
            $config['name'],
            $config['size'][0],
            $config['size'][1],
            !empty($config['mode']) ? $config['mode'] : ImageInterface::THUMBNAIL_OUTBOUND
        );
    }

    public static function getDefaultSizes(): array
    {
        return [
            'name' => 'Default size',
            'size' => [150, 150],
        ];
    }
}