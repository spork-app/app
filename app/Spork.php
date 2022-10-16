<?php

namespace App;

use App\Events\Spork\ActionRegistered;
use App\Events\Spork\AssetPublished;
use App\Events\Spork\FeatureRegistered;
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
            'slug' => Str::slug($featureName),
            'icon' => $icon,
            'path' => $path,
            'enabled' => config('spork.'.Str::slug($featureName).'.enabled', false),
        ];

        event(new FeatureRegistered($featureName, $icon, $path, config('spork.'.Str::slug($featureName).'.enabled', false)));
    }

    public static function publish(string $type, string $asset = null)
    {
        if (empty($asset)) {
            return static::$assets[$type];
        }

        static::$assets[$type][] = $asset;
        event(new AssetPublished($type, $asset));
    }

    public static function actions(string $feature, string $path)
    {
        $feature = Str::slug($feature);

        $actions = [
            $feature => [],
        ];

        foreach (glob($path.'/*.php') as $file) {
            $contents = file_get_contents($file);

            $basename = basename($file, '.php');

            if ($basename === 'ActionInterface') {
                continue;
            }

            if (stripos($contents, 'class '.$basename) === false) {
                continue;
            }

            preg_match('/namespace\s+(.*);/', $contents, $matches);

            $class = $matches[1].'\\'.$basename;

            $instance = new $class;

            $action = [
                'name' => $instance->getName(),
                'url' => $instance->getUrl(),
                'tags' => $instance->tags(),
            ];
            $actions[$feature][] = $action;
            Route::post($instance->getUrl(), $class);
            event(new ActionRegistered($feature, $action));
        }

        static::$actions = array_merge(
            static::$actions,
            $actions
        );

        return static::$actions;
    }

    public static function hasFeature(string $featureName)
    {
        $slug = Str::slug($featureName);

        return isset(self::$features[$slug]) && self::$features[$slug]['enabled'];
    }
}
