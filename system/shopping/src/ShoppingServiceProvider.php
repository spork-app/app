<?php

namespace Spork\Shopping;

use App\Spork;
use Illuminate\Support\ServiceProvider;

class ShoppingServiceProvider extends ServiceProvider
{
    public function register()
    {
        Spork::addFeature('shopping', 'ShoppingCartIcon', '/shopping');
    }
}
