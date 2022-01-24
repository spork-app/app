<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark:bg-gray-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>

        <script>
            @php 
            $features = array_map(fn($feature ) => [ 
                'name' => $feature['name'], 
                'icon' => $feature['icon'],
                'path' => $feature['path'],
                'slug' => Str::slug($feature['name']),
             ], App\Spork::$features);

            $actions = array_map(fn($action ) => $action, App\Spork::$actions);
            @endphp
            window.Features = @json($features);
            window.Actions = @json($actions);
        </script>
        @foreach (App\Spork::publish('css') as $asset)
            <link href="{{ $asset }}" rel="stylesheet"> 
        @endforeach

    </head>
    <body class="antialiased h-full text-gray-900">
        <div id="app" class="h-full">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

           <router-view></router-view>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
