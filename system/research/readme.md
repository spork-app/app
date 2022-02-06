## Research

Simply add to your spork app through composer!

```
composer require spork/research
```

Publish your assets

```
php artisan vendor:publish --provider=Spork\\Research\\ResearchServiceProvider
```

You'll need to run `artisan migrate` to ensure your database gets the new repeating events schema

Lastly, register the Service Provider in your Spork App's `config/app.php` file. That will automatically add the Research entry to the menu.