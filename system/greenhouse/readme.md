## Greenhouse

Simply add to your spork app through composer!

```
composer require spork/greenhouse
```

Publish your assets

```
php artisan vendor:publish --provider=Spork\\Greenhouse\\GreenhouseServiceProvider
```

You'll need to run `artisan migrate` to ensure your database gets the new repeating events schema

Lastly, register the Service Provider in your Spork App's `config/app.php` file. That will automatically add the Greenhouse entry to the menu.