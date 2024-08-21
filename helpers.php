<?php

use ArtMksh\LaravelActive\Contracts\Activity;

if (!function_exists('active')) {
    function active($routes = [], $class = null, $fallback = null)
    {
        $active = app(Activity::class);

        if (empty($routes)) {
            return $active;
        }

        return $active->active($routes, $class, $fallback);
    }
}

if (!function_exists('is_active')) {
    function is_active($routes): bool
    {
        return active()->is($routes);
    }
}