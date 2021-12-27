<?php

namespace App;

use Illuminate\Support\Str;

class Spork 
{
    public static $assets = [
        'css' => [],
        'js' => [],
    ];

    public static $features = [];

    public static function addFeature(string $featureName, string $icon, string $path)
    {
        self::$features[$featureName] = [
            'name' => Str::title($featureName),
            'icon' => $icon,
            'path' => $path
        ];
    }

    public static function publish(string $type, string $asset = null)
    {
        if (empty($asset)) {
            return static::$assets[$type];
        }

        static::$assets[$type][] = $asset;
    }
}