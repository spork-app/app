## Food

Simply add to your spork app through composer!

```
composer require spork/food
```

Publish your assets

```
php artisan vendor:publish --provider=Spork\\Food\\FoodServiceProvider
```

You'll need to run `artisan migrate` to ensure your database gets the new repeating events schema

Lastly, register the Service Provider in your Spork App's `config/app.php` file. That will automatically add the Food entry to the menu.