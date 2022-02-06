## Maintenance

Simply add to your spork app through composer!

```
composer require spork/maintenance
```

Publish your assets

```
php artisan vendor:publish --provider=Spork\\Maintenance\\MaintenanceServiceProvider
```

You'll need to run `artisan migrate` to ensure your database gets the new repeating events schema

Lastly, register the Service Provider in your Spork App's `config/app.php` file. That will automatically add the Maintenance entry to the menu.