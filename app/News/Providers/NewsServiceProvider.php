<?php

namespace App\News\Providers;

use App\News\Contracts\Service\NewsServiceContract;
use App\News\Service\NewsService;
use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(NewsServiceContract::class, NewsService::class);
    }
}
