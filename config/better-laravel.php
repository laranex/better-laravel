<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Route Loading
    |--------------------------------------------------------------------------
    |
    | When enabled, every PHP file under routes/web and routes/api is loaded
    | by the BetterLaravelServiceProvider with the "web" and "api" middleware
    | groups respectively. Disable it to register route files yourself.
    |
    */

    'enable_routes' => env('BETTER_LARAVEL_ENABLE_ROUTES', true),

    /*
    |--------------------------------------------------------------------------
    | Route Prefixes
    |--------------------------------------------------------------------------
    |
    | URI prefixes applied to the route files loaded from routes/web and
    | routes/api.
    |
    */

    'web_routes_prefix' => env('BETTER_LARAVEL_WEB_ROUTES_PREFIX', ''),

    'api_routes_prefix' => env('BETTER_LARAVEL_API_ROUTES_PREFIX', 'api'),

];
