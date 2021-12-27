<?php

namespace Spork\News;

use Spork\News\Contracts\Service\NewsServiceContract;
use Spork\News\Service\NewsService;
use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(NewsServiceContract::class, NewsService::class);
    }
}
