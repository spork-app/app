<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark dark:bg-gray-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
        
        <!-- favicon -->
        <link rel="icon" href="/favicon.ico" type="image/x-icon">

        <script>
            @php 
            $actions = array_map(fn($action ) => $action, Spork\Core\Spork::$actions);
            @endphp
            window.Features = @json(Spork\Core\Spork::$features);
        </script>
        @foreach (Spork\Core\Spork::publish('css') as $asset)
            <link href="{{ $asset }}" rel="stylesheet"> 
        @endforeach
    </head>
    <body id="body" class="antialiased h-full text-gray-900"
    data-features="{{ json_encode(Spork\Core\Spork::$features) }}"
    data-actions="{{ json_encode($actions) }}"
    data-load-with="{{ json_encode(Spork\Core\Spork::$loadWith) }}"
    data-provides="{{ json_encode(Spork\Core\Spork::provides()) }}"
    > 
        <div id="app" class="h-full">
           <router-view></router-view>
        </div>
        @auth
        @if (!file_exists(storage_path('app/setup.json')))
        <div id="data" data-env="{{ json_encode($_ENV) }}"></div>
        @endif
        @endauth

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
