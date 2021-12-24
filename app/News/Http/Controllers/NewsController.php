<?php

namespace App\News\Http\Controllers;

use App\News\Contracts\Service\NewsServiceContract;
use Illuminate\Http\Request;

class NewsController
{
    public function handle(Request $request, NewsServiceContract $newsService)
    {
        return $newsService->headlines($request->get('query'));
    }
}
