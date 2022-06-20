<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark:bg-gray-900">
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
            $actions = array_map(fn($action ) => $action, App\Spork::$actions);
            @endphp
            window.Features = @json(App\Spork::$features);
            window.Actions = @json($actions);
        </script>
        @foreach (App\Spork::publish('css') as $asset)
            <link href="{{ $asset }}" rel="stylesheet"> 
        @endforeach

    </head>
    <body class="antialiased h-full text-gray-900">
        <div id="app" class="h-full">
           <router-view></router-view>
        </div>

        @auth
        <div id="data" data-env="{{ json_encode($_ENV) }}"></div>
        @endauth

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
