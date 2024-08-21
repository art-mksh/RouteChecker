<?php

namespace ArtMksh\LaravelActive\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ManagesRoutes
{
    public function route($routes, ?string $class = null, ?string $fallback = null): ?string
    {
        $isActive = $this->isRoute($routes);
        return $this->getCssClass($isActive, $class, $fallback);
    }

    public function path($routes, ?string $class = null, ?string $fallback = null): ?string
    {
        $isActive = $this->isPath($routes);
        return $this->getCssClass($isActive, $class, $fallback);
    }

    public function isRoute($routes): bool
    {
        [$patterns, $ignored] = $this->parseRoutes(Arr::wrap($routes));

        return !$this->isIgnored($ignored) && $this->getRequest()->routeIs($patterns);
    }

    public function isPath($routes): bool
    {
        [$routes, $ignored] = $this->parseRoutes(Arr::wrap($routes));

        if ($this->isIgnored($ignored)) {
            return false;
        }

        return $this->getRequest()->is($routes) || $this->getRequest()->fullUrlIs($routes);
    }

    protected function isIgnored(array $ignored): bool
    {
        if (empty($ignored)) {
            return false;
        }

        return $this->isPath($ignored) || $this->isRoute($ignored);
    }

    protected function parseRoutes(array $allRoutes): array
    {
        $ignoredRoutes = [];
        $validRoutes = [];

        foreach ($allRoutes as $route) {
            if (Str::startsWith($route, 'not:')) {
                $ignoredRoutes[] = substr($route, 4);
            } else {
                $validRoutes[] = $route;
            }
        }

        return [$validRoutes, $ignoredRoutes];
    }
}