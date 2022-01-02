<?php

namespace App;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Spork 
{
    public static $assets = [
        'css' => [],
        'js' => [],
    ];

    public static $features = [];

    public static $actions = [];

    public static function addFeature(string $featureName, string $icon, string $path)
    {
        self::$features[Str::slug($featureName)] = [
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

    public static function actions(string $feature, string $path)
    {
        $feature = Str::slug($feature);

        $actions = [
            $feature => [],
        ];

        foreach (glob($path . '/*.php') as $file) {
            $contents = file_get_contents($file);

            $basename = basename($file, '.php');

            if ($basename === 'ActionInterface') {
                continue;
            }

            if (stripos($contents, 'class '. $basename) === false) {
                continue; 
            }

            preg_match('/namespace\s+(.*);/', $contents, $matches);

            $class = $matches[1].'\\'.$basename;

            $instance = new $class;

            $actions[$feature][] = [
                'name' => $instance->getName(),
                'url' => $instance->getUrl(),
                'tags' => $instance->tags(),
            ];
            Route::post($instance->getUrl(), $class);
        }

        static::$actions = array_merge(
            static::$actions,
            $actions
        );

        return static::$actions;
    }
}