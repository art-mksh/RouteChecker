<?php

namespace ArtMksh\LaravelActive;

use ArtMksh\LaravelActive\Contracts\Activity as ActiveContract;
use ArtMksh\LaravelActive\Traits\ManagesClasses;
use ArtMksh\LaravelActive\Traits\ManagesRequests;
use ArtMksh\LaravelActive\Traits\ManagesRoutes;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class Activity implements ActiveContract
{
    use ManagesClasses, ManagesRequests, ManagesRoutes;

    public function __construct(array $options)
    {
        $this->setActiveClass(Arr::get($options, 'class', 'active'));
        $this->setFallbackClass(Arr::get($options, 'fallback-class'));
    }

    public function active($routes, ?string $class = null, ?string $fallback = null): ?string
    {
        $isActive = $this->is($routes);
        return $this->getCssClass($isActive, $class, $fallback);
    }

    public function is($routes): bool
    {
        $routes = Arr::wrap($routes);

        return $this->isPath($routes) || $this->isRoute($routes);
    }
}